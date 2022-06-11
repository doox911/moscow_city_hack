<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder {
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {
    $menus = [
      [
        'title' => 'Домой',
        'name' => 'Дом',
        'icon_name' => 'home',
        'to' => '/',
        'order' => 0,
      ],
      [
        'title' => 'Профиль',
        'name' => 'Профиль',
        'icon_name' => 'perm_identity',
        'to' => '/profile',
        'order' => 1,
      ]
    ];

    foreach ($menus as $menu) {
      Menu::create($menu);
    }
  }
}
