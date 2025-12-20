<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RGPDController extends Controller
{
    public function cgu()
    {
        return view('rgpd.cgu');
    }

    public function mentionsLegales()
    {
        return view('rgpd.mention-legales');
    }
}
