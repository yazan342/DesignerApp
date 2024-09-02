<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Design;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function order(OrderRequest $request)
    {
        try {
            DB::beginTransaction();
            if (Auth::user()->is_designer) {
                return apiErrors("Designers cannot place orders.");
            }

            $design = Design::with(['colors', 'sizes'])->findOrFail($request->design_id);

            $color = $design->colors()->where('colors.id', $request->color_id)->first();
            if (!$color) {
                return apiErrors("This design does not have the specified color.");
            }

            $size = $design->sizes()->where('sizes.id', $request->size_id)->first();
            if (!$size) {
                return apiErrors("This design does not have the specified size.");
            }

            $colorHex = $color->hex;
            $sizeName = $size->size;

            $order = Order::create([
                'user_id' => Auth::id(),
                'design_id' => $design->id,
                'price' => $design->price,
                'color' => $colorHex,
                'size' => $sizeName,
            ]);
            DB::commit();
            return apiResponse("Order placed successfully", $order);
        } catch (Exception $e) {
            DB::rollBack();
            return apiErrors($e->getMessage());
        }
    }
}
