<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreSettingController extends Controller
{
    public function edit()
    {
        $settings = StoreSetting::current();
        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = StoreSetting::current();
        $data = $request->validate([
            'shop_name' => ['required', 'string', 'max:120'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:5120'],
            'remove_logo' => ['nullable', 'boolean'],
            'phone' => ['nullable', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:180'],
            'address' => ['nullable', 'string', 'max:255'],
            'instagram_url' => ['nullable', 'url:http,https', 'max:2048'],
            'zalo_url' => ['nullable', 'url:http,https', 'max:2048'],
            'messenger_url' => ['nullable', 'url:http,https', 'max:2048'],
            'badge_featured' => ['nullable', 'string', 'max:60'],
            'badge_best_seller' => ['nullable', 'string', 'max:60'],
            'badge_sale' => ['nullable', 'string', 'max:60'],
        ]);

        $oldLogoPath = $settings->logo_path;
        unset($data['logo'], $data['remove_logo']);

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('store/logos', 'public');
        } elseif ($request->boolean('remove_logo')) {
            $data['logo_path'] = null;
        }

        $settings->update($data);

        if ($oldLogoPath && $oldLogoPath !== $settings->logo_path) {
            Storage::disk('public')->delete($oldLogoPath);
        }

        return redirect()->route('admin.settings.edit')->with('status', 'Đã lưu thông tin cửa hàng.');
    }
}
