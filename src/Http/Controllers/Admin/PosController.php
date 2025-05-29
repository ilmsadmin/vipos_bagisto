<?php

namespace Zplus\Vipos\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PosController extends Controller
{
    /**
     * Display POS dashboard.
     */
    public function index()
    {
        return view('vipos::admin.pos.index');
    }
}
