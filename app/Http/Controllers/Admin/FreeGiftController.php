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
            'product_id' => 'required|exists:products,id',
        ]);

        Setting::set('free_gift_enabled', $request->enabled);
        Setting::set('free_gift_threshold', $request->threshold);
        Setting::set('free_gift_product_id', $request->product_id);

        return back()->with('success', 'Free gift settings saved!');
    }
}
