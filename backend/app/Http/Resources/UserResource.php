<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 *
 * @description Ресурс данных пользователя
 * @package User
 */
class UserResource extends JsonResource {

  /**
   * @param Request $request
   * @return array
   */
  public function toArray($request): array {
    /** @var User */
    $user = $this->resource;

    $company = $user->isOwnerRole() ? $user->counterparty : null;

    return [
      'id' => $user->id,
      'name' => $user->name,
      'second_name' => $user->second_name,
      'patronymic' => $user->patronymic,
      'email' => $user->email,
      'role' => $user->roles->first()->name ?? null,
      'email_verified_at' => $user->email_verified_at,
      'created_at' => $user->created_at,
      'updated_at' => $user->updated_at,
      'company' => $company,
    ];
  }
}
