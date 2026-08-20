<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSettingsRequest;
use App\Models\SystemSetting;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::orderBy('group')->get()->groupBy('group');
        return Inertia::render('Admin/Settings', ['settings' => $settings]);
    }

    public function update(SystemSetting $systemSetting, UpdateSettingsRequest $request)
    {
        if (!$systemSetting->is_editable) {
            return back()->with('error', 'This setting cannot be edited.');
        }

        $systemSetting->update(['value' => $request->input('value')]);

        return back()->with('success', 'Setting updated successfully.');
    }
}
