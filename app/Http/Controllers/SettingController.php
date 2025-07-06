<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

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



        DB::beginTransaction();

        try {
            $setting = Setting::firstOrNew();

            $data = [
                'store_name' => $request->store_name,
                'owner_name' => $request->owner_name,
                'address' => $request->address,
                'phone' => $request->phone,
            ];

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $publicPath = public_path('images/settings');
                
                // Create directory if not exists
                if (!File::exists($publicPath)) {
                    File::makeDirectory($publicPath, 0755, true);
                }

                // Delete old logo if exists
                if ($setting->logo && File::exists(public_path($setting->logo))) {
                    File::delete(public_path($setting->logo));
                }
                
                // Store new logo directly to public folder
                $logoName = time().'_'.$request->file('logo')->getClientOriginalName();
                $request->file('logo')->move($publicPath, $logoName);
                $data['logo'] = 'images/settings/'.$logoName;
            }

            $setting->fill($data);
            $setting->save();

            DB::commit();

            return redirect()->route('settings.index')->with('success', 'Pengaturan berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Delete the uploaded file if transaction fails
            if (isset($logoName) && isset($publicPath)) {
                $filePath = $publicPath.'/'.$logoName;
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui pengaturan: '.$e->getMessage());
        }
    }
} 