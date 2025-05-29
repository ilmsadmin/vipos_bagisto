@extends('admin::layouts.master')

@section('page_title')
    ViPOS - Bảng điều khiển
@stop

@section('content-wrapper')
    <div class="content full-page">
        <div class="page-content">
            <div class="page-header">
                <h1>ViPOS - Hệ thống bán hàng</h1>
                <p class="page-description">Quản lý bán hàng tại điểm bán</p>
            </div>

            <div class="page-content">
                <div class="grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                    <!-- Quick Stats -->
                    <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Phiên giao dịch hôm nay</h3>
                        <p class="text-3xl font-bold text-blue-600">0</p>
                    </div>

                    <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Tổng doanh thu</h3>
                        <p class="text-3xl font-bold text-green-600">0 VNĐ</p>
                    </div>

                    <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Giao dịch thành công</h3>
                        <p class="text-3xl font-bold text-purple-600">0</p>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-8">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Thao tác nhanh</h2>
                    <div class="flex gap-4">
                        <a href="{{ route('admin.vipos.sessions.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium">
                            Quản lý phiên giao dịch
                        </a>
                        <a href="{{ route('admin.vipos.transactions.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium">
                            Xem giao dịch
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
