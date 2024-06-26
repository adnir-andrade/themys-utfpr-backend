<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
use Core\Database\ActiveRecord\HasMany;
use DateTime;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * * @property int $player_id
 * * @property string $name
 * * @property int $level
 * * @property string|null $gender
 * * @property string $race
 * * @property string $klass
 * * @property int $klass_level
 * * @property int $hp
 * * @property int $strength
 * * @property int $dexterity
 * * @property int $constitution
 * * @property int $intelligence
 * * @property int $wisdom
 * * @property int $charisma
 * * @property string|null $background
 */
class Character extends Model
{
  protected static string $table = 'characters';
  protected static array $columns = [
    'player_id',
    'name',
    'level',
    'gender',
    'race',
    'klass',
    'klass_level',
    'hp',
    'strength',
    'dexterity',
    'constitution',
    'intelligence',
    'wisdom',
    'charisma',
    'background'
  ];

  protected ?string $gender = null;
  protected ?string $background = null;

  public function player(): BelongsTo
  {
    return $this->belongsTo(User::class, 'player_id');
  }

  public function campaigns_player(): BelongsTo
  {
    return $this->belongsTo(CampaignsPlayer::class, 'campaigns_player_id');
  }

  public function validates(): void
  {
    Validations::notEmpty('player_id', $this);
    Validations::notEmpty('name', $this);
    Validations::notEmpty('level', $this);
    Validations::notEmpty('race', $this);
    Validations::notEmpty('klass', $this);
    Validations::notEmpty('klass_level', $this);
    Validations::notEmpty('hp', $this);
    Validations::notEmpty('strength', $this);
    Validations::notEmpty('dexterity', $this);
    Validations::notEmpty('constitution', $this);
    Validations::notEmpty('intelligence', $this);
    Validations::notEmpty('wisdom', $this);
    Validations::notEmpty('charisma', $this);

    Validations::minValue('level', 1, $this);
    Validations::minValue('klass_level', 1, $this);
    Validations::minValue('hp', 1, $this);
    Validations::minValue('strength', 1, $this);
    Validations::minValue('dexterity', 1, $this);
    Validations::minValue('constitution', 1, $this);
    Validations::minValue('intelligence', 1, $this);
    Validations::minValue('wisdom', 1, $this);
    Validations::minValue('charisma', 1, $this);
  }

  public static function findByName(string $name): Character|null
  {
    return Character::findBy(['name' => $name]);
  }
}
