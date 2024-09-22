<?php

namespace App\Http\Controllers;

use App\DesignStatusEnum;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'mobile' => $request->mobile,
                'is_designer' => $request->is_designer,
                'address' => $request->address,
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;
            DB::commit();
            return apiResponse("User registered successfully", new UserResource($user, $token));
        } catch (Exception $e) {
            DB::rollBack();
            return apiErrors($e->getMessage());
        }
    }




    public function login(LoginRequest $request): JsonResponse
    {

        if (!Auth::attempt($request->only('email', 'password'))) {

            return apiErrors('Invalid login credentials');
        }

        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('auth_token')->plainTextToken;

        return apiResponse("Logged in successfully", new UserResource($user, $token));
    }

    public function updateUser(UpdateUserRequest $request): JsonResponse
    {

        try {

            DB::beginTransaction();

            $user = Auth::user();


            if ($request->has('name')) {
                $user->name = $request->input('name');
            }

            if ($request->has('email')) {
                $user->email = $request->input('email');
            }

            if ($request->has('mobile')) {
                $user->mobile = $request->input('mobile');
            }

            if ($request->has('address')) {
                $user->address = $request->input('address');
            }

            DB::commit();

            return apiResponse('User info updated successfully', $user);
        } catch (Exception $e) {
            DB::rollBack();
            return apiErrors($e->getMessage());
        }
    }


    public function getUserInfo(): JsonResponse
    {

        if (Auth::user()->is_designer == 1) {
            $user = User::query()
                ->where('id', Auth::user()->id)
                ->with(['designs' => function ($query) {
                    $query->where('status', DesignStatusEnum::Accepted)
                        ->with('category');
                }])
                ->first();
        } else {
            $user = User::query()->where('id', Auth::user()->id)->with('orders.design')->first();
        }
        return apiResponse('User information', $user);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return apiResponse('Logged out successfully');
    }


    public function deleteAccount(): JsonResponse
    {
        try {
            DB::beginTransaction();
            $user = User::query()->where('id', Auth::id())->first();
            $user->delete();
            DB::commit();
            return apiResponse('Account Deleted successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return apiErrors($e->getMessage());
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();

            if (!Hash::check($request->current_password, $user->password)) {
                return apiErrors('Current password is incorrect.');
            }

            $validated = $request->validated();

            $user->password = Hash::make($validated['new_password']);
            $user->save();
            DB::commit();
            return apiResponse('Password changed successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return apiErrors($e->getMessage());
        }
    }



    public function getDesigner($id)
    {
        try {

          $designer=  User::query()->findOrFail($id);

            return apiResponse("designer retrived successfully",$designer);
            
        } catch (Exception $e) {

            return apiErrors($e->getMessage());
        }
    }
}
