<?php

namespace Database\Populate;

use App\Models\User;

class UsersPopulate
{
  public static function populate(int $players_quantity, int $dms_quantity): void
  {
    $quantity = $players_quantity + $dms_quantity;
    $player_array = array_fill(0, $players_quantity, 'player');
    $dm_array = array_fill(0, $dms_quantity, 'dm');
    $roles = array_merge($dm_array, $player_array);

    shuffle($roles);

    for ($i = 1; $i <= $quantity; $i++) {
      $data = [
        'name' => 'Hades Clone ' . $i,
        'username' => 'Haotran_' . $i,
        'email' => 'ad' . $i . '@ad.com',
        'password' => 'bacon123',
        'password_confirmation' => 'bacon123',
        'role' => array_shift($roles),
        'profile_url' => './assets/image/profile/anon.jpg'
      ];

      $user = new User($data);
      $user->save();
    }

    echo "Users populated with $quantity registers\n";
  }
}
