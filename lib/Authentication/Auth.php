<?php

namespace Lib\Authentication;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth
{
  // Disclaimer: I know the key is exposed, but this is a college project after all.
  private static string $secretKey = 'th15k3y1s54f3';

  public static function login($user): string
  {
    $payload = [
      'iss' => 'themys.com',      // Emissor do token
      'sub' => $user->id,         // Assunto do token (ID do usuário)
      'iat' => time(),            // Tempo em que o token foi emitido
      'exp' => time() + 3600      // Tempo de expiração (1 hora)
    ];

    return JWT::encode($payload, self::$secretKey, 'HS256');
  }

  public static function validateToken($token): ?User
  {
    try {
      $decoded = JWT::decode($token, new Key(self::$secretKey, 'HS256'));
      return User::find($decoded->sub);
    } catch (\Exception $e) {
      return null;
    }
  }

  public static function user(): ?User
  {
    if (isset($_SESSION['user']['id'])) {
      $id = $_SESSION['user']['id'];
      return User::find($id);
    }

    return null;
  }

  public static function check(): bool
  {
    return isset($_SESSION['user']['id']) && self::user() !== null;
  }

  public static function logout(): void
  {
    unset($_SESSION['user']['id']);
  }
}
