<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;

class LegalController extends Controller
{
    // Affiche les CGU
    public function terms()
    {
        return view('rgpd.cgu');
    }

    // Affiche les mentions légales
    public function legalNotice()
    {
        return view('rgpd.mention-legales');
    }

    // Affiche la page 404
    public function notFound()
    {
        return view('rgpd.404');
    }
}
