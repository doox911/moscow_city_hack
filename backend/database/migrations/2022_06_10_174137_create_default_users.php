<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;

return new class extends Migration {

  private const DEFAULT_ROLES = [
    'admin',
    'government',
    'owner',
    'guest',
  ];

  private const DEFAULT_USERS = [
    [
      'name' => 'Тестовый администратор',
      'email' => 'admin@rosgosplan.ru',
      'password' => 'password',
      'roles' => ['admin'],
    ],
    [
      'name' => 'Тестовый правительственный пользователь',
      'email' => 'government@rosgosplan.ru',
      'password' => 'password',
      'roles' => ['government'],
    ],
    [
      'name' => 'Тестовый собственник компании',
      'email' => 'owner@rosgosplan.ru',
      'password' => 'password',
      'roles' => ['owner'],
    ],
    [
      'name' => 'Тестовый гость портала',
      'email' => 'guest@rosgosplan.ru',
      'password' => 'password',
      'roles' => ['guest'],
    ]
  ];

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    foreach (self::DEFAULT_ROLES as $role_canonical_name) {
      $role = new Role;
      $role->name = $role_canonical_name;
      $role->guard_name = 'web';
      $role->save();
    }

    foreach (self::DEFAULT_USERS as $user_data) {
      $user = new User;
      $user->password = Hash::make($user_data['password']);
      $user->email = $user_data['email'];
      $user->name = $user_data['name'];
      $user->save();

      foreach ($user_data['roles'] as $role_canonical_name) {
        $role = Role::where('name', $role_canonical_name)->first();
        $user->roles()->attach($role->id);
      }
    }
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    $user_emails = collect(self::DEFAULT_USERS)->pluck('email');

    $users = User::whereIn('email', $user_emails)->get();
    foreach ($users as $user) {
      $user->roles()->detach();
      $user->delete();
    }

    Role::query()->delete();
  }
};
