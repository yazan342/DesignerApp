<?php

namespace App\Http\Controllers;

use App\DesignStatusEnum;
use App\Models\Design;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function getPendingProducts()
    {
        $designs = Design::with(['designer', 'category', 'colors', 'sizes'])->where('status', DesignStatusEnum::Pending)->get();

        return apiResponse("Pending Designs Retrieved Successfully", $designs);
    }


    public function acceptDesign($id)
    {
        $design = Design::query()->findOrFail($id);

        $design->status = DesignStatusEnum::Accepted;
        $design->save();

        return apiResponse("Design Accepted Successfully");
    }



    public function rejectDesign($id)
    {
        $design = Design::query()->findOrFail($id);

        $design->status = DesignStatusEnum::Rejected;
        $design->save();

        return apiResponse("Design Rejected Successfully");
    }
}
