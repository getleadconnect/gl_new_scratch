<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class UserHelpController extends Controller
{
    /**
     * Display the user Help & Support page.
     */
    public function index(): View
    {
        return view('user.help.index', [
            'pageTitle' => 'Help & Support',
        ]);
    }
}
