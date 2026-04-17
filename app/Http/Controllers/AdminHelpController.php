<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AdminHelpController extends Controller
{
    /**
     * Display the admin Help & Support page.
     */
    public function index(): View
    {
        return view('admin.admin-help.index', [
            'pageTitle' => 'Help & Support',
        ]);
    }
}
