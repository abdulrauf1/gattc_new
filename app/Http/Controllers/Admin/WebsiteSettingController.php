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
            'vision' => ['nullable', 'string', 'max:3000'],
            'mission' => ['nullable', 'string', 'max:3000'],
            'objectives' => ['nullable', 'string', 'max:5000'],

            'principal_name' => ['nullable', 'string', 'max:255'],
            'principal_designation' => ['nullable', 'string', 'max:255'],
            'principal_photo' => ['nullable', 'string', 'max:500'],
            'principal_message' => ['nullable', 'string', 'max:2000'],

            'imc_chairman_name' => ['nullable', 'string', 'max:255'],
            'imc_chairman_designation' => ['nullable', 'string', 'max:255'],
            'imc_chairman_photo' => ['nullable', 'string', 'max:500'],
            'imc_chairman_message' => ['nullable', 'string', 'max:2000'],

            'md_name' => ['nullable', 'string', 'max:255'],
            'md_designation' => ['nullable', 'string', 'max:255'],
            'md_photo' => ['nullable', 'string', 'max:500'],
            'md_message' => ['nullable', 'string', 'max:2000'],
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