<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminProfileController extends Controller
{
    /**
     * Show the admin profile page.
     */
    public function index(): View
    {
        return view('admin.profile', [
            'pageTitle' => 'My Profile',
            'user'      => auth()->user(),
        ]);
    }

    /**
     * Update profile details.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:users,email,' . $user->id,
            'company_name' => 'nullable|string|max:255',
            'country_code' => 'nullable|string|max:10',
            'mobile'       => 'nullable|string|max:20',
            'address'      => 'nullable|string|max:500',
        ]);

        $user->update([
            'name'         => $request->name,
            'email'        => $request->email,
            'company_name' => $request->company_name,
            'country_code' => $request->country_code,
            'mobile'       => $request->mobile,
            'address'      => $request->address,
        ]);

        return response()->json(['success' => true, 'message' => 'Profile updated successfully.']);
    }

    /**
     * Change the admin's password.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();
        $user->update(['password' => Hash::make($request->new_password)]);

        return response()->json(['success' => true, 'message' => 'Password changed successfully.']);
    }
}
