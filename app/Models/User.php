<?php

namespace App\Models;

//use App\Services\ProfileAvatar;
use Core\Database\ActiveRecord\HasMany;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $encrypted_password
 * @property string $role
 * @property string $profile_url
 */
class User extends Model
{
  protected static string $table = 'users';
  protected static array $columns = ['name', 'username', 'email', 'encrypted_password', 'role', 'profile_url'];

  protected ?string $password = null;
  protected ?string $password_confirmation = null;

  public function campaigns(): HasMany
  {
    return $this->hasMany(Campaign::class, 'dm_id'); // user_id?
  }

  public function campaigns_players(): HasMany
  {
    return $this->hasMany(CampaignsPlayer::class, 'player_id'); //user_id?
  }

  public function validates(): void
  {
    Validations::notEmpty('name', $this);
    Validations::notEmpty('username', $this);
    Validations::notEmpty('email', $this);
    Validations::notEmpty('role', $this);
    Validations::notEmpty('profile_url', $this);

    self::validateUniqueness('email', $this);
    self::validateUniqueness('username', $this);

    if ($this->newRecord()) {
      Validations::passwordConfirmation($this);
    }
  }

  private function validateUniqueness(string $field): void
  {
    $existing = User::findBy([$field => $this->$field]);
    if ($existing && $existing->id !== $this->id) {
      Validations::uniqueness($field, $this);
    }
  }

  public function authenticate(string $password): bool
  {
    if ($this->encrypted_password == null) {
      return false;
    }

    return password_verify($password, $this->encrypted_password);
  }

  public static function findByEmail(string $email): User|null
  {
    return User::findBy(['email' => $email]);
  }

  public static function findByUsername(string $username): User|null
  {
    return User::findBy(['username' => $username]);
  }

  public static function find(int $id): ?User
  {
    return User::findBy(['id' => $id]);
  }

  public function __set(string $property, mixed $value): void
  {
    parent::__set($property, $value);

    if (
      $property === 'password' &&
      $this->newRecord() &&
      $value !== null && $value !== ''
    ) {
      $this->encrypted_password = password_hash($value, PASSWORD_DEFAULT);
    }
  }

//  public function avatar(): ProfileAvatar
//  {
//    return new ProfileAvatar($this);
//  }
}
