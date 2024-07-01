<?php

namespace Tests\Unit\Models\CampaignsPlayers;

use App\Models\CampaignsPlayer;
use App\Models\Campaign;
use App\Models\User;
use App\Models\Character;
use Tests\TestCase;

class CampaignsPlayerTest extends TestCase
{
  private User $user1;
  private User $user2;
  private User $user3;
  private Campaign $campaign1;
  private Campaign $campaign2;
  private Character $character1;
  private Character $character2;
  private Character $character3;
  private Character $character4;
  private CampaignsPlayer $party1_1;
  private CampaignsPlayer $party1_2;
  private CampaignsPlayer $party2_1;
  private CampaignsPlayer $party2_2;

  public function setUp(): void
  {
    parent::setUp();

    $this->user1 = new User([
      'name' => 'dm',
      'username' => 'dm',
      'email' => 'dm@example.com',
      'password' => '123456',
      'password_confirmation' => '123456',
      'role' => 'dm',
      'profile_url' => './assets/image/profile/anon.jpg'
    ]);
    $this->user1->save();

    $this->user2 = new User([
      'name' => 'player 1',
      'username' => 'player1',
      'email' => 'player1@example.com',
      'password' => '123456',
      'password_confirmation' => '123456',
      'role' => 'player',
      'profile_url' => './assets/image/profile/anon.jpg'
    ]);
    $this->user2->save();

    $this->user3 = new User([
      'name' => 'player 2',
      'username' => 'player2',
      'email' => 'player2@example.com',
      'password' => '123456',
      'password_confirmation' => '123456',
      'role' => 'player',
      'profile_url' => './assets/image/profile/anon.jpg'
    ]);
    $this->user3->save();

    $this->campaign1 = new Campaign([
      'name' => 'Campaign test 1',
      'dm_id' => $this->user1->id,
      'next_session' => '2024-06-30',
    ]);
    $this->campaign1->save();

    $this->campaign2 = new Campaign([
      'name' => 'Campaign test 2',
      'dm_id' => $this->user1->id,
      'next_session' => '2024-07-30',
    ]);
    $this->campaign2->save();

    $races = ['Human', 'Elf', 'Dwarf', 'Orc'];
    $klasses = ['Warrior', 'Mage', 'Rogue', 'Cleric'];
    $characters = [];
    for ($i = 1; $i <= 4; $i++) {
      $character = new Character([
        'player_id' => $i <= 2 ? $this->user2->id : $this->user3->id,
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
        'points_to_spend' => 0,
        'skills' => json_encode(['Stealth', 'Survival', 'Tracking']),
        'background' => 'Background information for character ' . $i,
      ]);
      $character->save();
      $characters[] = $character;
    }
    $this->character1 = $characters[0];
    $this->character2 = $characters[1];
    $this->character3 = $characters[2];
    $this->character4 = $characters[3];

    $this->party1_1 = new CampaignsPlayer([
      'player_id' => $this->user1->id,
      'campaign_id' => $this->campaign1->id,
      'character_id' => $this->character1->id,
    ]);
    $this->party1_1->save();

    $this->party1_2 = new CampaignsPlayer([
      'player_id' => $this->user2->id,
      'campaign_id' => $this->campaign1->id,
      'character_id' => $this->character2->id,
    ]);
    $this->party1_2->save();

    $this->party2_1 = new CampaignsPlayer([
      'player_id' => $this->user1->id,
      'campaign_id' => $this->campaign2->id,
      'character_id' => $this->character3->id,
    ]);
    $this->party2_1->save();

    $this->party2_2 = new CampaignsPlayer([
      'player_id' => $this->user2->id,
      'campaign_id' => $this->campaign2->id,
      'character_id' => $this->character4->id,
    ]);
    $this->party2_2->save();

  }

  public function test_should_create_new_campaignsplayer(): void
  {
    $this->assertCount(4, CampaignsPlayer::all());
  }

  public function test_all_should_return_all_campaigns_players(): void
  {
    $players[] = $this->party1_1->id;
    $players[] = $this->party1_2->id;
    $players[] = $this->party2_1->id;
    $players[] = $this->party2_2->id;

    $all = array_map(fn ($player) => $player->id, CampaignsPlayer::all());

    $this->assertCount(4, $all);
    $this->assertEquals($players, $all);
  }

  public function test_destroy_should_remove_the_campaigns_player(): void
  {
    $this->party1_1->destroy();
    $this->assertCount(3, CampaignsPlayer::all());
  }

  public function test_set_player_id(): void
  {
    $newPlayerId = $this->user1->id + 1;
    $this->party1_1->player_id = $newPlayerId;
    $this->assertEquals($newPlayerId, $this->party1_1->player_id);
  }

  public function test_set_campaign_id(): void
  {
    $newCampaignId = $this->campaign2->id + 1;
    $this->party1_1->campaign_id = $newCampaignId;
    $this->assertEquals($newCampaignId, $this->party1_1->campaign_id);
  }

  public function test_set_character_id(): void
  {
    $newCharacterId = $this->character4->id + 1;
    $this->party1_1->character_id = $newCharacterId;
    $this->assertEquals($newCharacterId, $this->party1_1->character_id);
  }

  public function test_find_players_by_campaign_id(): void
  {
    $playersInCampaign1 = CampaignsPlayer::findPlayersByCampaignId($this->campaign1->id);
    $playersInCampaign2 = CampaignsPlayer::findPlayersByCampaignId($this->campaign2->id);

    $this->assertCount(2, $playersInCampaign1);
    $this->assertCount(2, $playersInCampaign2);

    $this->assertEquals($this->user1->id, $playersInCampaign1[0]->id);
    $this->assertEquals($this->user2->id, $playersInCampaign1[1]->id);

    $this->assertEquals($this->user1->id, $playersInCampaign2[0]->id);
    $this->assertEquals($this->user2->id, $playersInCampaign2[1]->id);
  }

  public function test_find_characters_by_campaign_id(): void
  {
    $charactersInCampaign1 = CampaignsPlayer::findCharactersByCampaignId($this->campaign1->id);
    $charactersInCampaign2 = CampaignsPlayer::findCharactersByCampaignId($this->campaign2->id);

    $this->assertCount(2, $charactersInCampaign1);
    $this->assertCount(2, $charactersInCampaign2);

    $this->assertEquals($this->character1->id, $charactersInCampaign1[0]->id);
    $this->assertEquals($this->character2->id, $charactersInCampaign1[1]->id);

    $this->assertEquals($this->character3->id, $charactersInCampaign2[0]->id);
    $this->assertEquals($this->character4->id, $charactersInCampaign2[1]->id);
  }

  public function test_find_campaigns_by_player_id(): void
  {
    $campaignsForUser1 = CampaignsPlayer::findCampaignsByPlayerId($this->user1->id);
    $campaignsForUser2 = CampaignsPlayer::findCampaignsByPlayerId($this->user2->id);

    $this->assertCount(2, $campaignsForUser1);
    $this->assertCount(2, $campaignsForUser2);

    $this->assertEquals($this->campaign1->id, $campaignsForUser1[0]->id);
    $this->assertEquals($this->campaign2->id, $campaignsForUser1[1]->id);

    $this->assertEquals($this->campaign1->id, $campaignsForUser2[0]->id);
    $this->assertEquals($this->campaign2->id, $campaignsForUser2[1]->id);
  }

  public function test_find_campaign_by_character_id(): void
  {
    $campaignForCharacter1 = CampaignsPlayer::findCampaignByCharacterId($this->character1->id);
    $campaignForCharacter2 = CampaignsPlayer::findCampaignByCharacterId($this->character2->id);
    $campaignForCharacter3 = CampaignsPlayer::findCampaignByCharacterId($this->character3->id);
    $campaignForCharacter4 = CampaignsPlayer::findCampaignByCharacterId($this->character4->id);

    $this->assertEquals($this->campaign1->id, $campaignForCharacter1->id);
    $this->assertEquals($this->campaign1->id, $campaignForCharacter2->id);
    $this->assertEquals($this->campaign2->id, $campaignForCharacter3->id);
    $this->assertEquals($this->campaign2->id, $campaignForCharacter4->id);
  }
}
