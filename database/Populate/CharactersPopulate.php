<?php

namespace Database\Populate;

use App\Models\CampaignsPlayer;
use App\Models\Character;
use App\Models\User;

class CharactersPopulate
{
  public static function populate(): void
  {
    $campaigns_players = CampaignsPlayer::all();
    $races = ['Human', 'Elf', 'Dwarf', 'Orc', 'Gnome', 'Halfling'];
    $klasses = ['Warrior', 'Mage', 'Rogue', 'Cleric'];


    foreach ($campaigns_players as $i => $campaigns_player) {
      $data = [
        'player_id' => $campaigns_player->player_id,
        'name' => 'Character ' . $i,
        'level' => rand(1, 10),
        'gender' => rand(0, 1) ? 'Male' : 'Female',
        'race' => $races[array_rand($races)],
        'klass' => $klasses[array_rand($klasses)],
        'klass_level' => rand(1, 5),
        'hp' => rand(50, 100),
        'strength' => rand(8, 18),
        'dexterity' => rand(8, 18),
        'constitution' => rand(8, 18),
        'intelligence' => rand(8, 18),
        'wisdom' => rand(8, 18),
        'charisma' => rand(8, 18),
        'background' => 'Background information for character ' . $i,
      ];

      $character = new Character($data);
      $character->save();
      $campaigns_player->update(['character_id' => $character->id]);
    }

    $quantity = count($campaigns_players);
    echo "Characters populated with $quantity records\n";
  }
}
