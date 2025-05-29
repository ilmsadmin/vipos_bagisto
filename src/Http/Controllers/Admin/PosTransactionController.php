<?php

namespace Zplus\Vipos\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\Product\Models\Product;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerGroup;
use Webkul\Core\Repositories\ChannelRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class PosTransactionController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected ChannelRepository $channelRepository
    ) {}

    /**
     * Display POS transactions.
     */
    public function index()
    {
        return view('vipos::admin.transactions.index');
    }

    /**
     * Process checkout.
     */
    public function checkout(Request $request)
    {
        try {
            $request->validate([
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'customer_id' => 'nullable|exists:customers,id',
                'payment_method' => 'required|string',
                'total' => 'required|numeric|min:0'
            ]);

            // Here you would integrate with Bagisto's order creation system
            // For now, return success response
            return response()->json([
                'success' => true,
                'message' => 'Transaction completed successfully',
                'transaction_id' => 'TXN-' . time()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Checkout failed: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get products for POS.
     */
    public function getProducts(Request $request)
    {
        try {
            $query = Product::query()
                ->where('status', 1) // Only active products
                ->where('visible_individually', 1); // Only individually visible products

            // Search functionality
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('sku', 'like', '%' . $search . '%')
                      ->orWhereHas('attribute_values', function($av) use ($search) {
                          $av->where('text_value', 'like', '%' . $search . '%');
                      });
                });
            }

            // Category filter
            if ($request->has('category_id') && !empty($request->category_id)) {
                $query->whereHas('categories', function($c) use ($request) {
                    $c->where('category_id', $request->category_id);
                });
            }

            $products = $query->with(['attribute_values', 'images'])
                             ->paginate($request->get('limit', 20));

            // Format products for POS interface
            $formattedProducts = $products->map(function($product) {
                return [
                    'id' => $product->id,
                    'sku' => $product->sku,
                    'name' => $product->name ?? $product->sku,
                    'price' => $product->price ?? 0,
                    'image' => $product->base_image_url ?? null,
                    'quantity' => $product->totalQuantity(),
                    'is_saleable' => $product->isSaleable()
                ];
            });

            return response()->json([
                'success' => true,
                'products' => $formattedProducts,
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'total' => $products->total()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch products: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search customers.
     */
    public function searchCustomers(Request $request)
    {
        try {
            $query = Customer::query();

            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', '%' . $search . '%')
                      ->orWhere('last_name', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%')
                      ->orWhere('phone', 'like', '%' . $search . '%');
                });
            }

            $customers = $query->limit(20)->get();

            $formattedCustomers = $customers->map(function($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->first_name . ' ' . $customer->last_name,
                    'email' => $customer->email,
                    'phone' => $customer->phone ?? 'N/A'
                ];
            });

            return response()->json([
                'success' => true,
                'customers' => $formattedCustomers
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to search customers: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Quick create customer.
     */
    public function quickCreateCustomer(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:customers,email',
                'phone' => 'nullable|string|max:20',
                'password' => 'required|string|min:6'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get default customer group
            $defaultGroup = CustomerGroup::where('code', 'general')->first();
            if (!$defaultGroup) {
                $defaultGroup = CustomerGroup::first();
            }

            // Create customer
            $customer = Customer::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'customer_group_id' => $defaultGroup->id ?? 1,
                'channel_id' => $this->channelRepository->getCurrentChannelId(),
                'is_verified' => 1
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully',
                'customer' => [
                    'id' => $customer->id,
                    'name' => $customer->first_name . ' ' . $customer->last_name,
                    'email' => $customer->email,
                    'phone' => $customer->phone ?? 'N/A'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create customer: ' . $e->getMessage()
            ], 500);
        }
    }
}
