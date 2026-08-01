<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected array $keys = [
        'class_name', 'weekly_amount', 'cash_start_date',
        'treasurer_name', 'class_leader_name', 'school_name', 'class_logo',
    ];

    public function index()
    {
        $settings = [];
        foreach ($this->keys as $key) {
            $settings[$key] = Setting::get($key, config('kaskelas.' . $key));
        }
        $settings['weekly_amount'] = $settings['weekly_amount'] ?? config('kaskelas.weekly_amount');
        $settings['cash_start_date'] = $settings['cash_start_date'] ?? config('kaskelas.start_date');
        $settings['class_name'] = $settings['class_name'] ?? config('kaskelas.class_name');

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request, ActivityLogService $activityLog)
    {
        $data = $request->validate([
            'class_name' => ['required', 'string', 'max:100'],
            'weekly_amount' => ['required', 'integer', 'min:500'],
            'cash_start_date' => ['required', 'date'],
            'treasurer_name' => ['nullable', 'string', 'max:100'],
            'class_leader_name' => ['nullable', 'string', 'max:100'],
            'school_name' => ['nullable', 'string', 'max:150'],
        ]);

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        $activityLog->log('update', 'Memperbarui pengaturan kelas', null, null, $data);

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function uploadLogo(Request $request)
    {
        $request->validate(['logo' => ['required', 'image', 'max:2048']]);

        $path = $request->file('logo')->store('logos', 'public');
        Setting::set('class_logo', $path);

        return back()->with('success', 'Logo kelas berhasil diunggah.');
    }
}
