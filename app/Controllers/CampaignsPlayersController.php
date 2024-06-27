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
}
