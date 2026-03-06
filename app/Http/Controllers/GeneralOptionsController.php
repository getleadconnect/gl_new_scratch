<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GeneralOptionsController extends Controller
{
    /**
     * Show the General Options page.
     */
    public function index(): View
    {
        $userId = auth()->user()->id;

        $current  = Setting::where('settings_type','crm_api_token')->where('user_id',$userId)->pluck('status')->first();
        $apiStatus="Disabled";
        if($current)
            $apiStatus = $current===1?"Enabled":"Disabled";

        return view('user.settings.general', [
            'pageTitle'   => 'General Options',
            'otpEnabled'  => Setting::getValue($userId, 'otp_enabled', 'Disabled'),
            'crmToken'    => Setting::getValue($userId, 'crm_api_token', ''),
            'crmEnabled'  => $apiStatus,
        ]);

   }

    /**
     * Toggle OTP enabled status.
     */
    public function toggleOtp()
    {
        $userId  = auth()->user()->id;
        $current = Setting::getValue($userId, 'otp_enabled', 'Disabled');
        $new     = $current === 'Enabled' ? 'Disabled' : 'Enabled';

        Setting::setValue($userId, 'otp_enabled', $new);

        return response()->json(['success' => true, 'status' => $new, 'message' => 'OTP verification ' . $new . '.']);
    }

    /**
     * Update CRM API token.
     */
    public function updateCrmToken(Request $request)
    {
        $request->validate([
            'crm_api_token' => 'required|string|max:500',
        ]);

        if (substr($request->crm_api_token, 0, 3) !== 'gl_') {
            return response()->json(['success' => false, 'message' => 'Crm api token is invalid.'], 422);
        }

        $userId = auth()->user()->id;
        Setting::setValue($userId, 'crm_api_token', $request->crm_api_token);

        return response()->json(['success' => true, 'crmStatus' => 'Enabled', 'message' => 'CRM API token saved successfully.']);
    }

    /**
     * Remove CRM API token and disable the service.
     */
    public function removeCrmToken()
    {
        $userId = auth()->user()->id;

        Setting::where('user_id', $userId)
            ->where('settings_type', 'crm_api_token')
            ->delete();

        return response()->json(['success' => true, 'message' => 'CRM API token removed successfully.']);
    }

    /**
     * Toggle CRM API enabled status.
     */
    public function toggleCrm()
    {
        $userId  = auth()->user()->id;
   
            $current=Setting::where('settings_type','crm_api_token')->where('user_id',$userId)->first();
            
			if($current and $current->settings_value!="")
				{
                    $new=$current->status===1? 0 : 1;
					$current->status=$new;
					$current->save();
					$new_status=$new===1?"Enabled":"Disabled";

                    return response()->json(['success' => true, 'status' => $new_status, 'message' => 'CRM service ' . $new_status . '.']);
				}
				else 
				{
                    return response()->json(['success' => false, 'status' => "Disabled", 'message' => 'CRM service api token missing.!']);
				}

    }
}
