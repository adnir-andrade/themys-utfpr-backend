<?php

namespace App\Controllers;

use App\Models\User;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\Authentication\Auth;

class AuthenticationsController extends Controller
{
  public function authenticate(Request $request) : void
  {
    $params = json_decode($request->getBody(), true);

    $email = $params['email'];
    $password = $params['password'];

    $user = User::findByEmail($email);

    if ($user && $user->authenticate($password)) {
      $token = Auth::login($user);

      $userInfo = [
        'id' => $user->id,
        'name' => $user->name,
        'username' => $user->username,
        'email' => $user->email,
        'role' => $user->role
      ];

      echo json_encode(['token' => $token, 'user' => $userInfo]);
    } else {
      http_response_code(401);
      echo json_encode(['error' => 'Invalid credentials. Remember to login first.']);
    }
  }

  public function destroy(Request $request): void
  {
    // Retorna mensagem de sucesso porque, no momento, naão faz nada.
    // Se der tempo, armazenar token no banco de dados e depois deletar aqui.
    http_response_code(200);
    echo json_encode(['message' => 'Logout successfully! You need to manually delete your token for now because... Because yeah.']);
  }
}
