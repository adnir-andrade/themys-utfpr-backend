<?php

namespace App\Middleware;

use Core\Http\Middleware\Middleware;
use Core\Http\Request;
use Lib\Authentication\Auth;

class Authenticate implements Middleware
{
  public function handle(Request $request): void
  {
    $authHeader = $request->getHeader('Authorization');

    if ($authHeader) {
      $token = $authHeader;
      $user = Auth::validateToken($token);
      if ($user) {
        $request->user = $user;
        return;
      }
    }

    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
  }
}
