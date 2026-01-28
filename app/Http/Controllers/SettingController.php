<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    // Show settings page
    public function index()
    {
        $settings = Setting::all();
        return view('settings.index', compact('settings'));
    }

    // Show edit form
    public function edit()
    {
        $settings = Setting::all();
        return view('settings.edit', compact('settings'));
    }

    // Update settings
    public function update(Request $request)
    {
        foreach($request->settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully!');
    }
}
