<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeStatusRequest;
use App\Http\Requests\OrderRequest;
use App\Models\Design;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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



    public function changeStatus(ChangeStatusRequest $request)
    {
        try {


            DB::beginTransaction();
            if (!Auth::user()->is_designer) {
                return apiErrors("You are Not A designer.");
            }


            $order = Order::query()->with('design')->findOrFail($request->order_id);
            if ($order->design->designer_id != Auth::id()) {
                return apiErrors("Only the owner of the design can change the status of the order");
            }

            $order->status = $request->status;
            $order->save();
            DB::commit();
            return apiResponse("Order status changed successfully", $order);
        } catch (Exception $e) {
            DB::rollBack();
            return apiErrors($e->getMessage());
        }
    }
}
