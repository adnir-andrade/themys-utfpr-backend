<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property int player_id
 * @property int $campaign_id
 * @property int|null $character_id
 */
class CampaignsPlayer extends Model
{
  protected static string $table = 'campaigns_players';
  protected static array $columns = ['player_id', 'campaign_id', 'character_id'];

  protected ?string $character_id = null;


  public function player(): BelongsTo
  {
    return $this->belongsTo(User::class, 'player_id');
  }

  public function campaign(): BelongsTo
  {
    return $this->belongsTo(Campaign::class, 'campaign_id');
  }

  public function character(): BelongsTo
  {
    return $this->belongsTo(Character::class, 'character_id');
  }

  public function validates(): void
  {
    Validations::notEmpty('player_id', $this);
    Validations::notEmpty('campaign_id', $this);
  }

  public static function findByPlayer(int $player_id): CampaignsPlayer|null
  {
    return CampaignsPlayer::findBy(['player_id' => $player_id]);
  }

  public static function findByCampaign(int $campaign_id): CampaignsPlayer|null
  {
    return CampaignsPlayer::findBy(['campaign_id' => $campaign_id]);
  }

  public static function findByCharacter(int $character_id): CampaignsPlayer|null
  {
    return CampaignsPlayer::findBy(['character_id' => $character_id]);
  }
}
