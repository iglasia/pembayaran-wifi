<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Pastikan hanya role pemilik yang bisa akses
        $this->middleware(function ($request, $next) {
            if (auth()->user()->position_id != 1) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    /**
     * Display the settings form.
     */
    public function index()
    {
        $setting = Setting::first();
        return view('settings.index', compact('setting'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $setting = Setting::first();
        
        if (!$setting) {
            $setting = new Setting();
        }

        $data = [
            'store_name' => $request->store_name,
            'owner_name' => $request->owner_name,
            'address' => $request->address,
            'phone' => $request->phone,
        ];

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($setting->logo && Storage::disk('public')->exists($setting->logo)) {
                Storage::disk('public')->delete($setting->logo);
            }
            
            // Store new logo
            $logoPath = $request->file('logo')->store('images/settings', 'public');
            $data['logo'] = $logoPath;
        }

        $setting->fill($data);
        $setting->save();

        return redirect()->route('settings.index')->with('success', 'Pengaturan berhasil diperbarui!');
    }
} 