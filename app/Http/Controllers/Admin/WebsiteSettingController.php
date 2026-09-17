<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;

class WebsiteSettingController extends Controller
{
    /**
     * Display website settings.
     */
    public function index()
    {
        $settingRows = WebsiteSetting::query()
            ->pluck('value', 'key');

        $settings = [
            'site_name' => $settingRows->get('site_name', ''),
            'short_name' => $settingRows->get('short_name', ''),
            'address' => $settingRows->get('address', ''),
            'phone' => $settingRows->get('phone', ''),
            'email' => $settingRows->get('email', ''),
            'website' => $settingRows->get('website', ''),
            'facebook' => $settingRows->get('facebook', ''),
            'footer_text' => $settingRows->get('footer_text', ''),
        ];

        return view(
            'admin.website-settings.index',
            compact('settings')
        );
    }

    /**
     * Update website settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'short_name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'address' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:100',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'website' => [
                'nullable',
                'string',
                'max:255',
            ],

            'facebook' => [
                'nullable',
                'string',
                'max:255',
            ],

            'footer_text' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        foreach ($validated as $key => $value) {

            WebsiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with(
            'success',
            'Website settings updated successfully.'
        );
    }
}