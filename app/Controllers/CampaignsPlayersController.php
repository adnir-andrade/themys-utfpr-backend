<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use Core\Http\Request;
use App\Models\CampaignsPlayer;

class CampaignsPlayersController extends Controller
{
  public function index(Request $request): void
  {
    $paginator = CampaignsPlayer::paginate(page: $request->getParam('page', 1));
    $campaigns_players = $paginator->registers();

    $this->renderJson('campaigns_players/index', compact('paginator', 'campaigns_players'));
  }

  public function show(Request $request): void
  {
    $params = $request->getParams();

    $campaigns_player = CampaignsPlayer::findById($params['id']);

    $this->renderJson('campaigns_players/show', compact('campaigns_player'));
  }

  public function create(Request $request): void
  {
    $params = json_decode($request->getBody(), true);

    $campaign = new CampaignsPlayer($params['campaigns_player']);

    if ($campaign->save()) {
      http_response_code(201);
      echo json_encode(['message' => 'CampaignsPlayer registered!', 'campaigns_player' => $params]);
    } else {
      http_response_code(422);
      echo json_encode(['error' => 'Unprocessable Entity... Please, verify.']);
    }
  }

  public function update(Request $request): void
  {
    $id = $request->getParam('id');

    $params = json_decode($request->getBody(), true);

    $campaign = CampaignsPlayer::findById($id);
    if (!$campaign) {
      http_response_code(404);
      echo json_encode(['error' => 'CampaignsPlayer not found']);
      return;
    }

    if (isset($params['campaigns_player']))
      foreach ($params['campaigns_player'] as $key => $value)
        if ($campaign->$key !== $value) $campaign->$key = $value;

    if ($campaign->save()) {
      http_response_code(200);
      echo json_encode(['message' => 'CampaignsPlayer updated successfully!', 'campaigns_player' => $params]);
    } else {
      http_response_code(422);
      echo json_encode(['error' => 'Unprocessable Entity... Please, verify.']);
    }
  }


  public function destroy(Request $request): void
  {
    $params = $request->getParams();

    $campaign = CampaignsPlayer::findById($params['id']);

    if (!$campaign) {
      http_response_code(404);
      echo json_encode(['error' => 'CampaignsPlayer not found']);
      return;
    }

    $campaign->destroy();

    http_response_code(200);
    echo json_encode(['message' => 'CampaignsPlayer deleted successfully']);
  }

  public function playersByCampaignId(Request $request): void
  {
    $params = $request->getParams();
    $campaign_id = $params['id'];

    $players = CampaignsPlayer::findPlayersByCampaignId($campaign_id);

    $this->renderJson('campaigns_players/players_by_campaign', compact('players'));
  }

  public function charactersByCampaignId(Request $request): void
  {
    $params = $request->getParams();
    $campaign_id = $params['id'];

    $characters = CampaignsPlayer::findCharactersByCampaignId($campaign_id);

    $this->renderJson('campaigns_players/characters_by_campaign', compact('characters'));
  }

  public function campaignsByPlayerId(Request $request): void
  {
    $params = $request->getParams();
    $player_id = $params['id'];

    $campaigns = CampaignsPlayer::findCampaignsByPlayerId($player_id);

    $this->renderJson('campaigns_players/campaigns_by_player', compact('campaigns'));
  }

  public function campaignByCharacterId(Request $request): void
  {
    $params = $request->getParams();
    $character_id = $params['id'];

    $campaign = CampaignsPlayer::findCampaignByCharacterId($character_id);

    if ($campaign) {
      $this->renderJson('campaigns_players/campaign_by_character', compact('campaign'));
    } else {
      http_response_code(404);
      echo json_encode(['error' => 'Campaign not found for the specified character']);
    }
  }
}
