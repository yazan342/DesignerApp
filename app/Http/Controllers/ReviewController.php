<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddReviewRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Review;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function addReview(AddReviewRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $designId = $request->design_id;


            if ($user->is_designer) {
                return apiErrors("Designers cannot add reviews.");
            }

            $hasOrdered = Order::where('user_id', $user->id)
                ->where('design_id', $designId)
                ->exists();

            if (!$hasOrdered) {
                return apiErrors("You can only review designs that you have ordered.");
            }

            $existingReview = Review::where('user_id', $user->id)
                ->where('design_id', $designId)
                ->first();

            if ($existingReview) {
                $existingReview->rate = $request->rate;
                $existingReview->save();
                DB::commit();
                return apiResponse("Review updated successfully.");
            } else {
                Review::create([
                    'user_id' => $user->id,
                    'design_id' => $designId,
                    'rate' => $request->rate,
                ]);
                DB::commit();
                return apiResponse("Review added successfully.");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return apiErrors($e->getMessage());
        }
    }
}
