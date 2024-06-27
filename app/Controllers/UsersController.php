<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use Core\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
  public function index(Request $request): void
  {
    $paginator = User::paginate(page: $request->getParam('page', 1));
    $users = $paginator->registers();

    $this->renderJson('users/index', compact('paginator', 'users'));
  }

  public function show(Request $request): void
  {
    $params = $request->getParams();

    $user = User::findById($params['id']);

    $this->renderJson('users/show', compact('user'));
  }

  public function create(Request $request): void
  {
    $params = json_decode($request->getBody(), true);

    $user = new User($params['user']);

    if ($user->save()) {
      http_response_code(201);
      echo json_encode(['message' => 'User registered!', 'user' => $params]);
    } else {
      http_response_code(422);
      echo json_encode(['error' => 'Unprocessable Entity... Please, verify.']);
    }
  }

  public function update(Request $request): void
  {
    $id = $request->getParam('id');

    $params = json_decode($request->getBody(), true);

    $user = User::findById($id);
    if (!$user) {
      http_response_code(404);
      echo json_encode(['error' => 'User not found']);
      return;
    }

    if (isset($params['user']))
      foreach ($params['user'] as $key => $value)
        if ($user->$key !== $value) $user->$key = $value;

    if ($user->save()) {
      http_response_code(200);
      echo json_encode(['message' => 'User updated successfully!', 'user' => $params]);
    } else {
      http_response_code(422);
      echo json_encode(['error' => 'Unprocessable Entity... Please, verify.']);
    }
  }


  public function destroy(Request $request): void
  {
    $params = $request->getParams();

    $user = User::findById($params['id']);

    if (!$user) {
      http_response_code(404);
      echo json_encode(['error' => 'User not found']);
      return;
    }

    $user->destroy();

    http_response_code(200);
    echo json_encode(['message' => 'User deleted successfully']);
  }
}
