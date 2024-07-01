<?php

namespace Tests\Unit\Controllers;

use App\Models\Campaign;
use App\Models\CampaignsPlayer;
use App\Models\Character;
use App\Models\User;
use Core\Http\Request;

class CampaignsPlayersControllerTest extends ControllerTestCase
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

  public function test_index(): void
  {
    $response = $this->get('index', 'App\Controllers\CampaignsPlayersController');

    $this->assertMatchesRegularExpression("/{$this->party1_1->id}/", $response);
    $this->assertMatchesRegularExpression("/{$this->party1_2->id}/", $response);
    $this->assertMatchesRegularExpression("/{$this->party2_1->id}/", $response);
    $this->assertMatchesRegularExpression("/{$this->party2_2->id}/", $response);
  }

  public function test_show(): void
  {
    $_GET['id'] = $this->party1_1->id;
    $response = $this->get('show', 'App\Controllers\CampaignsPlayersController');

    $this->assertMatchesRegularExpression("/{$this->party1_1->id}/", $response);
  }

  public function test_create(): void
  {
    $body = json_encode([
      'campaigns_player' => [
        'player_id' => $this->user1->id,
        'campaign_id' => $this->campaign1->id,
        'character_id' => $this->character1->id,
      ]
    ]);

    $this->request->setBody($body);

    $response = $this->post('create', 'App\Controllers\CampaignsPlayersController');

    $this->assertMatchesRegularExpression("/CampaignsPlayer registered!/", $response);
  }

  public function test_update(): void
  {
    $body = json_encode([
      'campaign_player' => [
        'id_character' => $this->character2->id,
      ]
    ]);

    $_SERVER['REQUEST_METHOD'] = 'PUT';

    $this->request = new Request();
    $this->request->setBody($body);

    $this->request->addParams(['id' => $this->party1_1->id]);

    $controller = new \App\Controllers\CampaignsPlayersController();
    ob_start();
    $controller->update($this->request);
    $response = ob_get_clean();

    $this->assertMatchesRegularExpression("/CampaignsPlayer updated successfully!/", $response);
    $this->assertMatchesRegularExpression("/2/", $response);
  }

  public function test_destroy(): void
  {
    $_SERVER['REQUEST_METHOD'] = 'DELETE';

    $this->request = new Request();
    $this->request->addParams(['id' => $this->party1_1->id]);

    $controller = new \App\Controllers\CampaignsPlayersController();
    ob_start();
    $controller->destroy($this->request);
    $response = ob_get_clean();

    $this->assertMatchesRegularExpression("/CampaignsPlayer deleted successfully/", $response);
    $this->assertNull(CampaignsPlayer::findById($this->party1_1->id));
  }
}
