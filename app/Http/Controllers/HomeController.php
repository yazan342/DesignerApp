<?php

namespace App\Http\Controllers;

use App\DesignStatusEnum;
use App\Http\Requests\SearchDesignsRequest;
use App\Models\Category;
use App\Models\Design;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function getHomePage(): JsonResponse
    {
        if (Auth::user()->is_designer == 0) {
            $categories = Category::query()
                ->with(['designs' => function ($query) {
                    $query->where('status', DesignStatusEnum::Accepted)
                        ->orderBy('created_at', 'desc');
                }])
                ->get();
            return apiResponse("Categories Retrieved Successfully", $categories);
        } else {
            $designs = Design::query()
                ->where('designer_id', Auth::user()->id)
                ->where('status', DesignStatusEnum::Accepted)
                ->orderBy('created_at', 'desc')
                ->get();
            return apiResponse("Designs Retrieved Successfully", $designs);
        }
    }


    public function searchDesigns(SearchDesignsRequest $request): JsonResponse
    {
        try {
            $query = Design::query()
                ->where('status', DesignStatusEnum::Accepted);

            if ($request->filled('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }

            if ($request->filled('description')) {
                $query->where('description', 'like', '%' . $request->description . '%');
            }

            if ($request->filled('category')) {
                $categoryId = Category::where('name', 'like', '%' . $request->category . '%')->pluck('id')->first();
                if ($categoryId) {
                    $query->where('category_id', $categoryId);
                }
            }

            if ($request->filled('prepare_duration')) {
                $query->where('prepare_duration', $request->prepare_duration);
            }

            $designs = $query->get();

            return apiResponse("Search results retrieved successfully.", $designs);
        } catch (Exception $e) {
            return apiErrors($e->getMessage());
        }
    }
}
