<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles as SpatieHasRoles;

/**
 * Class User
 *
 * @method createToken($name, $scopes = []):\Laravel\Passport\PersonalAccessTokenResult
 * @method hasRole($roles, $guard = null): bool
 * @method getRoleNames(): Collection
 * @method getAllPermissions(): Collection
 */
class User extends Authenticatable {
  use HasApiTokens, HasFactory, Notifiable, SpatieHasRoles;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'email',
    'password',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  /**
   * Администратор ли это
   *
   * @return bool
   */
  public function isAdminRole(): bool {
    return $this->hasRole('admin');
  }

  /**
   * Правительственный пользователь ли это
   *
   * @return bool
   */
  public function isGovernmentRole(): bool {
    return $this->hasRole('government');
  }

  /**
   * Собственник компании ли это
   *
   * @return bool
   */
  public function isOwnerRole(): bool {
    return $this->hasRole('owner');
  }

  /**
   * Гость портала ли это
   *
   * @return bool
   */
  public function isGuestRole(): bool {
    return $this->hasRole('guest');
  }

}
