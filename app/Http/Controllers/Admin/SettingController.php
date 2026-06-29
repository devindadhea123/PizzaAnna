<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }
    
    public function index()
    {
        $settings = [
            'prediction_day' => Setting::get('prediction_day', 27),
            'prediction_hour' => Setting::get('prediction_hour', 12),
            'prediction_minute' => Setting::get('prediction_minute', 0),
            'prediction_enabled' => Setting::get('prediction_enabled', 1),
        ];
        
        return view('admin.pengaturan-jadwal', compact('settings'));
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'prediction_day' => 'required|integer|min:1|max:31',
            'prediction_hour' => 'required|integer|min:0|max:23',
            'prediction_minute' => 'required|integer|min:0|max:59',
            'prediction_enabled' => 'required|boolean',
        ]);
        
        Setting::set('prediction_day', $request->prediction_day);
        Setting::set('prediction_hour', $request->prediction_hour);
        Setting::set('prediction_minute', $request->prediction_minute);
        Setting::set('prediction_enabled', $request->prediction_enabled);
        
        return redirect()->back()->with('success', 'Jadwal prediksi berhasil diupdate!');
    }
}