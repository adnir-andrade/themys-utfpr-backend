<?php

namespace Database\Populate;

use App\Models\Campaign;
use App\Models\User;

class CampaignsPopulate
{
  public static function populate(int $quantity): void
  {
    $users = User::all();
    $dms = self::filterDms($users);

    for ($i = 1; $i <= $quantity; $i++) {
      $dm = array_shift($dms);
      $data = [
        'name' => 'Herathor ' . $i,
        'dm_id' => $dm->id,
        'next_session' => date_create()->format('Y-m-d')
      ];

      $campaign = new Campaign($data);
      $campaign->save();
    }

    echo "Campaigns populated with $quantity registers\n";
  }

  private static function filterDms(array &$users): array
  {
    return array_filter($users, fn($user) => $user->role === "dm");
  }
}
