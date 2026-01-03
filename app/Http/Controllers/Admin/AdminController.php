<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    // Affiche le dashboard admin
    public function index()
    {
        return view('admin.index');
    }
}
