<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;

class FreeGiftController extends Controller
{
    public function index()
    {
        $settings = [
            'enabled' => Setting::where('key', 'free_gift_enabled')->value('value') ?? 'true',
            'threshold' => Setting::where('key', 'free_gift_threshold')->value('value') ?? '1499',
            'product_id' => Setting::where('key', 'free_gift_product_id')->value('value'),
            'name' => Setting::where('key', 'free_gift_name')->value('value') ?? 'Herbal Immunity Booster (Sample)',
            'image' => Setting::where('key', 'free_gift_image')->value('value') ?? '',
        ];
        $products = Product::active()->orderBy('name')->get();
        return view('admin.free-gift.index', compact('settings', 'products'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'enabled' => 'required|in:true,false',
            'threshold' => 'required|numeric|min:0',
            'name' => 'required|string|max:255',
            'image' => 'nullable|string|max:500',
            'product_id' => 'nullable|exists:products,id',
        ]);

        $fields = [
            'free_gift_enabled' => $request->enabled,
            'free_gift_threshold' => $request->threshold,
            'free_gift_name' => $request->name,
            'free_gift_image' => $request->image,
            'free_gift_product_id' => $request->product_id,
        ];

        foreach ($fields as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value ?? '']);
        }

        return back()->with('success', 'Free gift settings saved!');
    }
}
