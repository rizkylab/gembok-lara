<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = \App\Models\AppSetting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            \App\Models\AppSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
            
            // Clear cache for this setting
            \Illuminate\Support\Facades\Cache::forget("app_setting_{$key}");
        }

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }
}
