<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Models\AppSetting;

class SettingsController extends Controller
{
    public function index()
    {
        $isDown = file_exists(storage_path('framework/down'));
        $app = [
            'name' => config('app.name'),
            'env' => config('app.env'),
            'debug' => config('app.debug'),
            'url' => config('app.url'),
            'timezone' => config('app.timezone'),
        ];
        $branding = [
            'site_name' => optional(AppSetting::where('key','site_name')->first())->value,
            'site_logo_path' => optional(AppSetting::where('key','site_logo_path')->first())->value,
            'site_favicon_path' => optional(AppSetting::where('key','site_favicon_path')->first())->value,
        ];
        return view('admin.settings.index', compact('isDown','app','branding'));
    }

    public function enableMaintenance(Request $request)
    {
        $data = $request->validate([
            'secret' => ['nullable','string','max:64'],
            'allow_ips' => ['nullable','string','max:500'],
        ]);
        $options = [];
        if (!empty($data['secret'])) {
            $options['--secret'] = $data['secret'];
        }
        if (!empty($data['allow_ips'])) {
            // Multiple --allow options are allowed
            $ips = collect(preg_split('/[\s,]+/', $data['allow_ips']))->filter()->all();
            foreach ($ips as $ip) { $options['--allow'][] = $ip; }
        }
        Artisan::call('down', $options);
        return back()->with('status', 'Maintenance mode enabled');
    }

    public function disableMaintenance(Request $request)
    {
        Artisan::call('up');
        return back()->with('status', 'Maintenance mode disabled');
    }

    public function clearCaches()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        return back()->with('status', 'Caches cleared');
    }

    public function updateBranding(Request $request)
    {
        $data = $request->validate([
            'site_name' => ['nullable','string','max:255'],
            'logo' => ['nullable','image','max:2048'],
            'favicon' => ['nullable','image','max:1024'],
        ]);

        if (isset($data['site_name'])) {
            AppSetting::updateOrCreate(['key' => 'site_name'], ['value' => $data['site_name']]);
        }
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('brand', 'public');
            AppSetting::updateOrCreate(['key' => 'site_logo_path'], ['value' => $path]);
        }
        if ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store('brand', 'public');
            AppSetting::updateOrCreate(['key' => 'site_favicon_path'], ['value' => $path]);
        }

        return back()->with('status', 'Branding updated');
    }
}
