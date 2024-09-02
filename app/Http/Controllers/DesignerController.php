<?php

namespace App\Http\Controllers;

use App\DesignStatusEnum;
use App\Http\Requests\CreateDesignRequest;
use App\Http\Requests\EditDesignRequest;
use App\Models\Category;
use App\Models\Color;
use App\Models\Design;
use App\Models\Size;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DesignerController extends Controller
{

    public function getCategories(): JsonResponse
    {

        $categories = Category::all();
        return apiResponse("Categories Retrieved Successfully", $categories);
    }


    public function getColors(): JsonResponse
    {

        $colors = Color::all();
        return apiResponse("Colors Retrieved Successfully", $colors);
    }



    public function getSizes(): JsonResponse
    {

        $sizes = Size::all();
        return apiResponse("Sizes Retrieved Successfully", $sizes);
    }




    public function createDesign(CreateDesignRequest $request): JsonResponse
    {

        try {
            if (Auth::user()->is_designer) {
                $validated = $request->validated();
                DB::beginTransaction();
                $design = Design::create([
                    'name' => $validated['name'],
                    'description' => $validated['description'],
                    'category_id' => $validated['category_id'],
                    'prepare_duration' => $validated['prepare_duration'],
                    'image' => uploadImage($request->file('image')),
                    'price' => $validated['price'],
                    'designer_id' => Auth::id(),
                ]);

                $design->sizes()->attach($validated['sizes']);
                $design->colors()->attach($validated['colors']);
                DB::commit();

                return apiResponse('Design created successfully', $design);
            } else {
                return apiErrors("Only Designers Can Add Designs");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return apiErrors($e->getMessage());
        }
    }


    public function deleteDesign($id): JsonResponse
    {

        try {
            $design = Design::query()->findOrFail($id);
            if (Auth::id() != $design->designer_id) {
                return apiErrors("Only the Design Owner Can Delete This Design");
            } else {
                $design->sizes()->detach();
                $design->colors()->detach();
                $design->delete();
                return apiResponse("Design Deleted Successfully");
            }
        } catch (Exception $e) {
            return apiErrors($e->getMessage());
        }
    }

    public function updateDesign(EditDesignRequest $request, $id): JsonResponse
    {

        try {
            $design = Design::query()->findOrFail($id);
            if (Auth::id() != $design->designer_id) {
                return apiErrors("Only the Design Owner Can Edit This Design");
            } else {
                if ($request->has('name')) {
                    $design->name = $request->name;
                }

                if ($request->has('description')) {
                    $design->description = $request->description;
                }
                if ($request->has('category_id')) {
                    $design->category_id = $request->category_id;
                }
                if ($request->has('prepare_duration')) {
                    $design->prepare_duration = $request->prepare_duration;
                }
                if ($request->has('price')) {
                    $design->price = $request->price;
                }
                if ($request->has('sizes')) {
                    $design->sizes()->syncWithoutDetaching($request->sizes);
                }
                if ($request->has('colors')) {
                    $design->colors()->syncWithoutDetaching($request->colors);
                }
                if ($request->hasFile('image')) {
                    Storage::disk('public')->delete('designs/' . basename($design->image));

                    $design->image =  uploadImage($request->file('image'));
                }


                return apiResponse("Design Updated Successfully", $design);
            }
        } catch (Exception $e) {
            return apiErrors($e->getMessage());
        }
    }

    public function getDesign($id)
    {
        try {
            $design = Design::query()
                ->where('status', DesignStatusEnum::Accepted)
                ->with(['colors', 'sizes'])
                ->findOrFail($id);

            return apiResponse("Design Retrieved Successfully", $design);
        } catch (Exception $e) {
            return apiErrors($e->getMessage());
        }
    }
}
