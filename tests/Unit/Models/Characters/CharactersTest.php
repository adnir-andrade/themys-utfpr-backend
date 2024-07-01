<?php

namespace Tests\Unit\Models\Characters;

use App\Models\Character;
use App\Models\User;
use Tests\TestCase;

class CharactersTest extends TestCase
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

  public function test_should_create_new_character(): void
  {
    $this->assertCount(2, Character::all());
  }

  public function test_all_should_return_all_characters(): void
  {
    $characters[] = $this->character1->id;
    $characters[] = $this->character2->id;

    $all = array_map(fn ($character) => $character->id, Character::all());

    $this->assertCount(2, $all);
    $this->assertEquals($characters, $all);
  }

  public function test_destroy_should_remove_the_character(): void
  {
    $this->character1->destroy();
    $this->assertCount(1, Character::all());
  }

  public function test_set_player_id(): void
  {
    $newPlayerId = $this->user->id + 1;
    $this->character1->player_id = $newPlayerId;
    $this->assertEquals($newPlayerId, $this->character1->player_id);
  }

  public function test_set_name(): void
  {
    $newName = 'New Character Name';
    $this->character1->name = $newName;
    $this->assertEquals($newName, $this->character1->name);
  }

  public function test_set_level(): void
  {
    $newLevel = 15;
    $this->character1->level = $newLevel;
    $this->assertEquals($newLevel, $this->character1->level);
  }

  public function test_set_gender(): void
  {
    $newGender = "Female";
    $this->character1->gender = $newGender;
    $this->assertEquals($newGender, $this->character1->gender);
  }

  public function test_set_race(): void
  {
    $newRace = "Dwarf";
    $this->character1->race = $newRace;
    $this->assertEquals($newRace, $this->character1->race);
  }

  public function test_set_klass(): void
  {
    $newKlass = "Fighter";
    $this->character1->klass = $newKlass;
    $this->assertEquals($newKlass, $this->character1->klass);
  }

  public function test_set_klass_level(): void
  {
    $newKlassLevel = 7;
    $this->character1->klass_level = $newKlassLevel;
    $this->assertEquals($newKlassLevel, $this->character1->klass_level);
  }

  public function test_set_hp(): void
  {
    $newHp = 70;
    $this->character1->hp = $newHp;
    $this->assertEquals($newHp, $this->character1->hp);
  }

  public function test_set_strength(): void
  {
    $newStrength = 20;
    $this->character1->strength = $newStrength;
    $this->assertEquals($newStrength, $this->character1->strength);
  }

  public function test_set_dexterity(): void
  {
    $newDexterity = 15;
    $this->character1->dexterity = $newDexterity;
    $this->assertEquals($newDexterity, $this->character1->dexterity);
  }

  public function test_set_constitution(): void
  {
    $newConstitution = 18;
    $this->character1->constitution = $newConstitution;
    $this->assertEquals($newConstitution, $this->character1->constitution);
  }

  public function test_set_intelligence(): void
  {
    $newIntelligence = 14;
    $this->character1->intelligence = $newIntelligence;
    $this->assertEquals($newIntelligence, $this->character1->intelligence);
  }

  public function test_set_wisdom(): void
  {
    $newWisdom = 16;
    $this->character1->wisdom = $newWisdom;
    $this->assertEquals($newWisdom, $this->character1->wisdom);
  }

  public function test_set_charisma(): void
  {
    $newCharisma = 12;
    $this->character1->charisma = $newCharisma;
    $this->assertEquals($newCharisma, $this->character1->charisma);
  }

  public function test_set_points_to_spend(): void
  {
    $newPoints = 5;
    $this->character1->points_to_spend = $newPoints;
    $this->assertEquals($newPoints, $this->character1->points_to_spend);
  }

  public function test_set_skills(): void
  {
    $newSkills = json_encode(['Acrobatics', 'Athletics']);
    $this->character1->skills = $newSkills;
    $this->assertEquals($newSkills, $this->character1->skills);
  }

  public function test_set_background(): void
  {
    $newBackground = 'Updated background information';
    $this->character1->background = $newBackground;
    $this->assertEquals($newBackground, $this->character1->background);
  }
}
