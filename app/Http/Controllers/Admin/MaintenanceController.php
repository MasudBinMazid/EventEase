<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaintenanceSettings;

class MaintenanceController extends Controller
{
    public function index()
    {
        $settings = MaintenanceSettings::getSettings();
        return view('admin.maintenance.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'is_enabled' => 'required|boolean',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'estimated_completion' => 'nullable|date|after:now',
            'allowed_ips' => 'nullable|string'
        ]);

        // Process allowed IPs
        $allowedIps = null;
        if ($request->filled('allowed_ips')) {
            $ips = array_map('trim', explode(',', $request->allowed_ips));
            $ips = array_filter($ips); // Remove empty values
            $allowedIps = array_values($ips); // Re-index array
        }

        $settings = MaintenanceSettings::first();
        
        if (!$settings) {
            $settings = new MaintenanceSettings();
        }

        $settings->fill([
            'is_enabled' => $request->boolean('is_enabled'),
            'title' => $request->title,
            'message' => $request->message,
            'estimated_completion' => $request->estimated_completion,
            'allowed_ips' => $allowedIps,
            'updated_by' => auth()->id()
        ]);

        $settings->save();

        $statusText = $request->boolean('is_enabled') ? 'enabled' : 'disabled';
        
        return redirect()->route('admin.maintenance.index')
            ->with('success', "Maintenance mode has been {$statusText} successfully!");
    }

    public function toggle(Request $request)
    {
        $settings = MaintenanceSettings::getSettings();
        
        if (!$settings->exists) {
            $settings->updated_by = auth()->id();
            $settings->save();
        }

        $settings->update([
            'is_enabled' => !$settings->is_enabled,
            'updated_by' => auth()->id()
        ]);

        $statusText = $settings->is_enabled ? 'enabled' : 'disabled';
        
        return response()->json([
            'success' => true,
            'is_enabled' => $settings->is_enabled,
            'message' => "Maintenance mode has been {$statusText}!"
        ]);
    }
}
