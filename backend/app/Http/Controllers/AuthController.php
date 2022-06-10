<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class AuthController extends Controller {

  /**
   * @param Request $request
   * @return JsonResponse
   */
  public function getUser(Request $request): JsonResponse {
    $result = [
      'messages' => ['Действия произведены'],
      'content' => UserResource::make($request->user()),
    ];

    return response()->json($result);
  }

  /**
   * @param Request $request
   * @return JsonResponse
   */
  public function updateUser(Request $request): JsonResponse {
    $validatedData = $request->validate([
      'id' => 'required|int',
      'name' => 'required|string|max:255',
      'second_name' => 'required|string|max:255',
      'patronymic' => 'string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:8',
    ]);

    $user = User::where('id', $validatedData['id'])->first();

    $validatedData['password'] = Hash::make($validatedData['password']);
    $user->update($validatedData);

    $user->roles()->detach();

    $user_role = Role::where('name', $validatedData['role'])->first();
    $user->roles()->attach($user_role->id);

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
      'access_token' => $token,
      'token_type' => 'Bearer',
    ]);
  }

  /**
   * @param Request $request
   * @return JsonResponse
   */
  public function register(Request $request): JsonResponse {
    $validatedData = $request->validate([
      'name' => 'required|string|max:255',
      'second_name' => 'required|string|max:255',
      'patronymic' => 'string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:8',
    ]);

    $user = User::create([
      'name' => $validatedData['name'],
      'second_name' => $validatedData['second_name'],
      'patronymic' => $validatedData['patronymic'],
      'email' => $validatedData['email'],
      'password' => Hash::make($validatedData['password']),
    ]);

    $user_role = Role::where('name', $validatedData['role'])->first();
    $user->roles()->attach($user_role->id);

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
      'access_token' => $token,
      'token_type' => 'Bearer',
    ]);
  }

  /**
   * @param Request $request
   * @return JsonResponse
   */
  public function login(Request $request): JsonResponse {
    if (!Auth::attempt($request->only('email', 'password'))) {
      return response()->json([
        'message' => 'Invalid login details'
      ], 401);
    }

    $user = User::where('email', $request['email'])->firstOrFail();

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
      'access_token' => $token,
      'token_type' => 'Bearer',
    ]);
  }

  /**
   * @param Request $request
   * @return JsonResponse
   */
  public function logout(Request $request): JsonResponse {
    if ($request->user()) {
      $request->user()->currentAccessToken()->delete();
    }

    return response()->json([
      'is_logout' => true
    ]);
  }
}
