<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StoreSettingController extends Controller
{
    /**
     * Display the store settings.
     */
    public function index()
    {
        $setting = StoreSetting::with(['codMethod', 'bkashMethod', 'nagadMethod', 'rocketMethod'])->first();

        return Inertia::render('store-settings/Index', [
            'setting' => $setting,
        ]);
    }


    /**
     * Show the form for editing store settings.
     */
    public function edit()
    {
        $setting = StoreSetting::first();

        return Inertia::render('store-settings/Index', [
            'setting' => $setting,
        ]);
    }

    /**
     * Update or create store settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'store_tagline' => 'nullable|string|max:255',
            'store_logo' => 'nullable',
            'store_favicon' => 'nullable',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'currency' => 'nullable|string|max:10',
            'currency_symbol' => 'nullable|string|max:10',
            'maintenance_mode' => 'boolean',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'tiktok_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'enable_cod' => 'boolean',
            'enable_bkash' => 'boolean',
            'enable_nagad' => 'boolean',
            'enable_rocket' => 'boolean',
            'bkash_account_number' => 'nullable|string|max:255',
            'nagad_account_number' => 'nullable|string|max:255',
            'rocket_account_number' => 'nullable|string|max:255',
            'bkash_qr_code_file' => 'nullable',
            'nagad_qr_code_file' => 'nullable',
            'rocket_qr_code_file' => 'nullable',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'products_title' => 'nullable|string|max:255',
            'customer_info_title' => 'nullable|string|max:255',
            'customer_info_label' => 'boolean',
            'customer_info_name_label' => 'nullable|string|max:255',
            'customer_info_phone_label' => 'nullable|string|max:255',
            'customer_info_email_label' => 'nullable|string|max:255',
            'customer_info_address_label' => 'nullable|string|max:255',
            'delivery_zone_title' => 'nullable|string|max:255',
            'additional_note_title' => 'nullable|string|max:255',
            'order_summary_title' => 'nullable|string|max:255',
            'payment_title' => 'nullable|string|max:255',
            'submit_button' => 'nullable|string|max:255',
        ]);

        $setting = StoreSetting::first();

        // ✅ Handle logo upload
        if ($request->hasFile('store_logo')) {
            if ($setting && $setting->store_logo && file_exists(public_path($setting->store_logo))) {
                @unlink(public_path($setting->store_logo));
            }
            $path = $request->file('store_logo')->store('store', 'public');
            $validated['store_logo'] = 'storage/' . $path;
        } elseif ($setting) {
            $validated['store_logo'] = $setting->store_logo;
        }

        // ✅ Handle favicon upload
        if ($request->hasFile('store_favicon')) {
            if ($setting && $setting->store_favicon && file_exists(public_path($setting->store_favicon))) {
                @unlink(public_path($setting->store_favicon));
            }
            $path = $request->file('store_favicon')->store('store', 'public');
            $validated['store_favicon'] = 'storage/' . $path;
        } elseif ($setting) {
            $validated['store_favicon'] = $setting->store_favicon;
        }

        // ✅ Save store settings
        if ($setting) {
            $setting->update($validated);
        } else {
            $setting = StoreSetting::create($validated);
        }

        // ✅ Payment methods
        $payments = [
            [
                'name' => 'Cash on Delivery',
                'code' => 'cod',
                'provider' => null,
                'status' => $request->enable_cod,
                'account_number' => null,
                'file_key' => null,
            ],
            [
                'name' => 'Bkash',
                'code' => 'bkash',
                'provider' => 'Bkash',
                'status' => $request->enable_bkash,
                'account_number' => $request->bkash_account_number,
                'file_key' => 'bkash_qr_code_file',
            ],
            [
                'name' => 'Nagad',
                'code' => 'nagad',
                'provider' => 'Nagad',
                'status' => $request->enable_nagad,
                'account_number' => $request->nagad_account_number,
                'file_key' => 'nagad_qr_code_file',
            ],
            [
                'name' => 'Rocket',
                'code' => 'rocket',
                'provider' => 'Rocket',
                'status' => $request->enable_rocket,
                'account_number' => $request->rocket_account_number,
                'file_key' => 'rocket_qr_code_file',
            ],
        ];

foreach ($payments as $p) {
    $payment = PaymentMethod::firstOrNew(['code' => $p['code']]);
    $payment->name = $p['name'];
    $payment->type = $p['type'] ?? 'mobile';
    $payment->provider = $p['provider'];
    $payment->account_number = $p['account_number'];
    $payment->status = $p['status'];

    $fileKey1 = strtolower($p['code']) . '_qr_code';       // Vue field
    $fileKey2 = strtolower($p['code']) . '_qr_code_file';  // alt name

    // ✅ If an image file is uploaded (from v-model ImageUploader)
    if ($request->hasFile($fileKey1)) {
        if ($payment->qr_code && file_exists(public_path($payment->qr_code))) {
            @unlink(public_path($payment->qr_code));
        }
        $path = $request->file($fileKey1)->store('payment_qr', 'public');
        $payment->qr_code = 'storage/' . $path;
    }
    elseif ($request->hasFile($fileKey2)) {
        if ($payment->qr_code && file_exists(public_path($payment->qr_code))) {
            @unlink(public_path($payment->qr_code));
        }
        $path = $request->file($fileKey2)->store('payment_qr', 'public');
        $payment->qr_code = 'storage/' . $path;
    }
    elseif ($request->input($fileKey1) && !str_contains($request->input($fileKey1), 'blob')) {
        // If frontend sends an existing URL instead of a new file
        $payment->qr_code = $request->input($fileKey1);
    }

    $payment->save();
}


        return redirect()->route('settings.index')->with('success', 'Settings updated successfully!');
    }
}
