<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use Core\Http\Request;
use App\Models\Campaign;

class CampaignsController extends Controller
{
  public function index(Request $request): void
  {
    $paginator = Campaign::paginate(page: $request->getParam('page', 1));
    $campaigns = $paginator->registers();

    $this->renderJson('campaigns/index', compact('paginator', 'campaigns'));
  }

  public function show(Request $request): void
  {
    $params = $request->getParams();

    $campaign = Campaign::findById($params['id']);

    $this->renderJson('campaigns/show', compact('campaign'));
  }

  public function create(Request $request): void
  {
    $params = json_decode($request->getBody(), true);

    $campaign = new Campaign($params['campaign']);

    if ($campaign->save()) {
      http_response_code(201);
      echo json_encode(['message' => 'Campaign registered!', 'campaign' => $params]);
    } else {
      http_response_code(422);
      echo json_encode(['error' => 'Unprocessable Entity... Please, verify.']);
    }
  }

  public function update(Request $request): void
  {
    $id = $request->getParam('id');

    $params = json_decode($request->getBody(), true);

    $campaign = Campaign::findById($id);
    if (!$campaign) {
      http_response_code(404);
      echo json_encode(['error' => 'Campaign not found']);
      return;
    }

    if (isset($params['campaign']))
      foreach ($params['campaign'] as $key => $value)
        if ($campaign->$key !== $value) $campaign->$key = $value;

    if ($campaign->save()) {
      http_response_code(200);
      echo json_encode(['message' => 'Campaign updated successfully!', 'campaign' => $params]);
    } else {
      http_response_code(422);
      echo json_encode(['error' => 'Unprocessable Entity... Please, verify.']);
    }
  }


  public function destroy(Request $request): void
  {
    $params = $request->getParams();

    $campaign = Campaign::findById($params['id']);

    if (!$campaign) {
      http_response_code(404);
      echo json_encode(['error' => 'Campaign not found']);
      return;
    }

    $campaign->destroy();

    http_response_code(200);
    echo json_encode(['message' => 'Campaign deleted successfully']);
  }

  public function findByDmId(Request $request): void
  {
    $params = $request->getParams();
    $dm_id = $params['id'];

    $campaigns = Campaign::findByDmId($dm_id);

    if ($campaigns) {
      $this->renderJson('campaigns/by_dm', compact('campaigns'));
    } else {
      http_response_code(404);
      echo json_encode(['error' => 'No campaigns found for the specified DM']);
    }
  }
}
