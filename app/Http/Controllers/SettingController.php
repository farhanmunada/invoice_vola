<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $setting = Setting::first();
        return view('settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'shop_name' => 'required|string|max:255',
            'shop_address' => 'nullable|string|max:255',
            'shop_phone' => 'nullable|string|max:20',
            'shop_email' => 'nullable|email|max:255',
            'footer_note' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $setting = Setting::first();
        
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('uploads', 'public');
            $validated['logo_path'] = $path;
            unset($validated['logo']); // Remove file object, we only need the path
        } else {
            unset($validated['logo']); // Remove null/extra key if no file uploaded
        }

        $setting->update($validated);

        return redirect()->route('dashboard')->with('status', 'Settings updated successfully.');
    }
}
