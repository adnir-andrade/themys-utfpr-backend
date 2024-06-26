<?php

namespace Database\Populate;

use App\Models\Campaign;
use App\Models\CampaignsPlayer;
use App\Models\User;

class CampaignsPlayersPopulate
{
  public static function populate($party_size): void
  {
    $campaigns = Campaign::all();
    $users = User::all();
    $users_id = self::getAttributeValues($users, "id");
    $dms_id = self::getAttributeValues($campaigns, "dm_id");
    $players_id = self::filterUsers($users_id, $dms_id);
    shuffle($players_id);

    foreach ($campaigns as $campaign) {
      for ($i = 1; $i <= $party_size; $i++) {
        $data = [
          'player_id' => array_shift($players_id),
          'campaign_id' => $campaign->id,
        ];
        $campaigns_player = new CampaignsPlayer($data);
        $campaigns_player->save();
      }
    }

    $quantity = count($campaigns);

    echo "CampaignsPlayers populated $quantity campaings with $party_size party members each\n";
  }

  private static function getAttributeValues(array &$entities, string $attributeName): array
  {
    return array_map(fn($entity) => $entity->{$attributeName}, $entities);
  }

  private static function filterUsers(array &$users, array $dms_id): array
  {
    return array_filter($users, fn($userId) => !in_array($userId, $dms_id));
  }
}
