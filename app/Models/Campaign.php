<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
use Core\Database\ActiveRecord\HasMany;
use DateTime;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property string $name
 * @property int $dm_id
 * @property DateTime|null $next_session
 */
class Campaign extends Model
{
  protected static string $table = 'campaigns';
  protected static array $columns = ['dm_id', 'name', 'next_session'];

  protected ?string $next_session = null;

  public function campaigns_players(): HasMany
  {
    return $this->hasMany(CampaignsPlayer::class, 'campaign_id');
  }

  public function players(): HasMany
  {
    return $this->hasMany(User::class, 'campaign_id');
  }

  public function dungeonMaster(): BelongsTo
  {
    return $this->belongsTo(User::class, 'dm_id');
  }

  public function validates(): void
  {
    Validations::notEmpty('dm_id', $this);
    Validations::notEmpty('name', $this);
  }

  public static function findByDmId(int $dm_id): array | null
  {
    return Campaign::where(['dm_id' => $dm_id]);
  }

  public static function findByName(string $name): Campaign|null
  {
    return Campaign::findBy(['name' => $name]);
  }
}
