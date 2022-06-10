<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Database\Eloquent\Builder;
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
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:8',
      'role' => 'required|string',
    ]);

    $user = User::where('id', $validatedData['id'])->first();

    $validatedData['password'] = Hash::make($validatedData['password']);
    $user->update($validatedData);

    $user->roles()->detach();

    $user_role = Role::where('name', $validatedData['role'])->first();

    $current_role_name = $user->roles->first()->name ?? null;

    if ($user_role->name !== 'admin' && $current_role_name === 'admin') {
      $is_last_admin = User::whereHas('roles', function (Builder $builder) use ($user_role) {
          $builder->where('role_id', $user_role->id);
        })->get()->count() === 1;

      if ($is_last_admin) {
        $result = [
          'messages' => ['Невозможно убрать роль администратора у единственного администратора'],
          'content' => [],
        ];

        return response()->json($result);
      }
    }

    $user->roles()->attach($user_role->id);

    $token = $user->createToken('auth_token')->plainTextToken;

    $result = [
      'messages' => ['Данные пользователя обновлены'],
      'content' => [
        'access_token' => $token,
        'token_type' => 'Bearer',
      ],
    ];

    return response()->json($result);
  }

  /**
   * @param Request $request
   * @return JsonResponse
   */
  public function register(Request $request): JsonResponse {
    $validatedData = $request->validate([
      'name' => 'required|string|max:255',
      'second_name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:8',
      'role' => 'required|string',
    ]);

    $user = User::create([
      'name' => $validatedData['name'],
      'second_name' => $validatedData['second_name'],
      'patronymic' => $validatedData['patronymic'] ?? null,
      'email' => $validatedData['email'],
      'password' => Hash::make($validatedData['password']),
    ]);

    $user_role = Role::where('name', $validatedData['role'])->first();
    $user->roles()->attach($user_role->id);

    $token = $user->createToken('auth_token')->plainTextToken;

    $result = [
      'messages' => ['Пользователь успешно создан'],
      'content' => [
        'access_token' => $token,
        'token_type' => 'Bearer',
      ],
    ];

    return response()->json($result);
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

    $result = [
      'messages' => ['Пользователь успешно авторизован'],
      'content' => [
        'access_token' => $token,
        'token_type' => 'Bearer',
      ],
    ];

    return response()->json($result);
  }

  /**
   * @param Request $request
   * @return JsonResponse
   */
  public function logout(Request $request): JsonResponse {
    if ($request->user()) {
      $request->user()->currentAccessToken()->delete();
    }

    $result = [
      'messages' => ['Пользователь вышел из системы'],
      'content' => [
        'is_logout' => true,
      ],
    ];

    return response()->json($result);
  }
}
