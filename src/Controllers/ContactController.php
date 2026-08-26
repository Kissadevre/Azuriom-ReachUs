<?php

namespace Azuriom\Plugin\ReachUs\Controllers;

use Azuriom\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class ContactController extends Controller
{
    /**
     * Display the public contact page.
     */
    public function index(): View
    {
        return view('reachus::index');
    }
}
