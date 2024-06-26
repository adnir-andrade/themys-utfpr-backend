<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use Core\Http\Request;
use App\Models\Character;

class CharactersController extends Controller
{
  public function index(Request $request): void
  {
    $characters = Character::all();
    $this->renderJson('characters/index', compact('characters'));
  }

  public function show(Request $request): void
  {
    $params = $request->getParams();

    $character = Character::findById($params['id']);

    $this->renderJson('characters/show', compact('character'));
  }

  public function create(Request $request): void
  {
    $params = json_decode($request->getBody(), true);
    $characterData = $params['character'];

    $character = new Character($characterData);

    if ($character->save()) {
      http_response_code(201);
      echo json_encode(['message' => 'Character registered!', 'character' => $characterData]);
    } else {
      http_response_code(422);
      echo json_encode(['error' => 'Unprocessable Entity... Please, verify.']);
    }
  }

  public function update(Request $request): void
  {
    $id = $request->getParam('id');

    $params = json_decode($request->getBody(), true);

    $character = Character::findById($id);
    if (!$character) {
      http_response_code(404);
      echo json_encode(['error' => 'Character not found']);
      return;
    }

    if (isset($params['character']))
      foreach ($params['character'] as $key => $value)
        if ($character->$key !== $value) $character->$key = $value;

    if ($character->save()) {
      http_response_code(200);
      echo json_encode(['message' => 'Character updated successfully!', 'character' => $params]);
    } else {
      http_response_code(422);
      echo json_encode(['error' => 'Unprocessable Entity... Please, verify.']);
    }
  }


  public function destroy(Request $request): void
  {
    $params = $request->getParams();

    $character = Character::findById($params['id']);

    if (!$character) {
      http_response_code(404);
      echo json_encode(['error' => 'Character not found']);
      return;
    }

    $character->destroy();

    http_response_code(200);
    echo json_encode(['message' => 'Character deleted successfully']);
  }

  public function getCharactersByPlayer(Request $request): void
  {
    $playerId = $request->getParam('player_id');

    if (!$playerId) {
      http_response_code(400);
      echo json_encode(['error' => 'Player ID is required']);
      return;
    }

    $characters = Character::findByPlayerId($playerId);

    $this->renderJson('characters/index', compact('characters'));
  }
}
