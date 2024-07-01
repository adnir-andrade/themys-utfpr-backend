<?php

namespace Tests\Unit\Controllers;

use App\Models\Campaign;
use App\Models\Character;
use App\Models\User;
use Core\Http\Request;

class CharactersControllerTest extends ControllerTestCase
{
  private User $user;
  private Character $character1;
  private Character $character2;

  public function setUp(): void
  {
    parent::setUp();

    $this->user = new User([
      'name' => 'player',
      'username' => 'player',
      'email' => 'player@example.com',
      'password' => '123456',
      'password_confirmation' => '123456',
      'role' => 'player',
      'profile_url' => './assets/image/profile/anon.jpg'
    ]);
    $this->user->save();

    $races = ['Human', 'Elf', 'Dwarf', 'Orc'];
    $klasses = ['Warrior', 'Mage', 'Rogue', 'Cleric'];
    $characters = [];
    for ($i = 1; $i <= 2; $i++) {
      $character = new Character([
        'player_id' => $this->user->id,
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
  }

  public function test_index(): void
  {
    $response = $this->get('index', 'App\Controllers\CharactersController');

    $this->assertMatchesRegularExpression("/{$this->character1->name}/", $response);
    $this->assertMatchesRegularExpression("/{$this->character2->name}/", $response);
  }

  public function test_show(): void
  {
    $_GET['id'] = $this->character1->id;
    $response = $this->get('show', 'App\Controllers\CharactersController');

    $this->assertMatchesRegularExpression("/{$this->character1->name}/", $response);
  }

  public function test_create(): void
  {
    $body = json_encode([
      'character' => [
        'player_id' => $this->user->id,
        'name' => 'Character Please Work',
        'level' => 7,
        'gender' => 'Male',
        'race' => 'Dwarf',
        'klass' => 'Paladin',
        'klass_level' => 7,
        'hp' => 77,
        'strength' => 18,
        'dexterity' => 17,
        'constitution' => 13,
        'intelligence' => 12,
        'wisdom' => 10,
        'charisma' => 13,
        'points_to_spend' => 0,
        'skills' => ['Persuasion', 'Survival', 'Intimidation'],
        'background' => 'Background information for character',
      ]
    ]);

    $this->request->setBody($body);

    $response = $this->post('create', 'App\Controllers\CharactersController');

    $this->assertMatchesRegularExpression("/Character registered!/", $response);

    $expectedResponse = json_encode([
      "message" => "Character registered!",
      "character" => [
        "player_id" => $this->user->id,
        "name" => 'Character Please Work',
        "level" => 7,
        "gender" => 'Male',
        "race" => 'Dwarf',
        "klass" => 'Paladin',
        "klass_level" => 7,
        "hp" => 77,
        "strength" => 18,
        "dexterity" => 17,
        "constitution" => 13,
        "intelligence" => 12,
        "wisdom" => 10,
        "charisma" => 13,
        "points_to_spend" => 0,
        "skills" => ['Persuasion', 'Survival', 'Intimidation'],
        "background" => 'Background information for character',
      ]
    ]);

    $this->assertJsonStringEqualsJsonString($expectedResponse, $response);
  }

  public function update(Request $request): void
  {
    $params = json_decode($request->getBody(), true);
    $characterData = $params['character'];

    $character = Character::findById($request->getParams()['id']);
    if ($character) {
      $character->update($characterData);

      if ($character->save()) {
        http_response_code(200);
        echo json_encode(['message' => 'Character updated successfully!', 'character' => $characterData]);
      } else {
        http_response_code(422);
        echo json_encode(['error' => 'Unprocessable Entity... Please, verify.']);
      }
    } else {
      http_response_code(404);
      echo json_encode(['error' => 'Character not found']);
    }
  }

  public function test_destroy(): void
  {
    $_SERVER['REQUEST_METHOD'] = 'DELETE';

    $this->request = new Request();
    $this->request->addParams(['id' => $this->character1->id]);

    $controller = new \App\Controllers\CharactersController();
    ob_start();
    $controller->destroy($this->request);
    $response = ob_get_clean();

    $this->assertMatchesRegularExpression("/Character deleted successfully/", $response);
    $this->assertNull(Character::findById($this->character1->id));
  }
}
