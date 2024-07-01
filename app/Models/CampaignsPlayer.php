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

  public static function findPlayersByCampaignId(int $campaign_id): array|null
  {
    $campaignPlayers = CampaignsPlayer::where(['campaign_id' => $campaign_id]);
    $players = [];
    foreach ($campaignPlayers as $campaignPlayer) {
      $players[] = User::findById($campaignPlayer->player_id);
    }
    return $players;
  }

  public static function findCharactersByCampaignId(int $campaign_id): array|null
  {
    $campaignPlayers = CampaignsPlayer::where(['campaign_id' => $campaign_id]);
    $characters = [];
    foreach ($campaignPlayers as $campaignPlayer) {
      $characters[] = Character::findById($campaignPlayer->character_id);
    }
    return $characters;
  }

  public static function findCampaignsByPlayerId(int $player_id): array|null
  {
    $campaignPlayers = CampaignsPlayer::where(['player_id' => $player_id]);
    $campaigns = [];
    foreach ($campaignPlayers as $campaignPlayer) {
      $campaigns[] = Campaign::findById($campaignPlayer->campaign_id);
    }
    return $campaigns;
  }

  public static function findCampaignByCharacterId(int $character_id): Campaign|null
  {
    $campaignPlayer = CampaignsPlayer::findBy(['character_id' => $character_id]);
    if ($campaignPlayer) {
      return Campaign::findById($campaignPlayer->campaign_id);
    }
    return null;
  }
}
