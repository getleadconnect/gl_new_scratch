<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HelpController extends Controller
{
    /**
     * Display the Help & Support page.
     */
    public function index(): View
    {
        return view('admin.help.index', [
            'pageTitle' => 'Help & Support',
        ]);
    }
}
