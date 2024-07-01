<?php

namespace Tests\Unit\Controllers;

use App\Models\User;
use Core\Http\Request;

class UsersControllerTest extends ControllerTestCase
{
  private User $user;

  public function setUp(): void
  {
    parent::setUp();

    $this->user = new User([
      'name' => 'player',
      'username' => 'player',
      'email' => 'player@example.com',
      'password' => '123456',
      'password_confirmation' => '123456',
      'role' => 'player',
      'profile_url' => './assets/image/profile/anon.jpg'
    ]);
    $this->user->save();
    $_SESSION['user']['id'] = $this->user->id;
  }

  public function test_index(): void
  {
    $response = $this->get('index', 'App\Controllers\UsersController');

    $this->assertMatchesRegularExpression("/{$this->user->name}/", $response);
    $this->assertMatchesRegularExpression("/{$this->user->name}/", $response);
  }

  public function test_show(): void
  {
    $_GET['id'] = $this->user->id;
    $response = $this->get('show', 'App\Controllers\UsersController');

    $this->assertMatchesRegularExpression("/{$this->user->name}/", $response);
  }

  public function test_create(): void
  {
    $body = json_encode([
      'user' => [
        'name' => 'John Snow',
        'username' => 'knownothing',
        'email' => 'jsn@example.com',
        'password' => '123456',
        'password_confirmation' => '123456',
        'role' => 'player',
        'profile_url' => './assets/image/profile/anon.jpg'
      ]
    ]);

    $this->request->setBody($body);

    $response = $this->post('create', 'App\Controllers\UsersController');

    $this->assertMatchesRegularExpression("/User registered!/", $response);
    $this->assertMatchesRegularExpression("/John Snow/", $response);
  }

  public function test_update(): void
  {
    $body = json_encode([
      'user' => [
        'name' => 'Yok',
        'profile_url' => './assets/image/profile/js.jpg'
      ]
    ]);

    $_SERVER['REQUEST_METHOD'] = 'PUT';

    $this->request = new Request();
    $this->request->setBody($body);

    $this->request->addParams(['id' => $this->user->id]);

    $controller = new \App\Controllers\UsersController();
    ob_start();
    $controller->update($this->request);
    $response = ob_get_clean();

    $this->assertMatchesRegularExpression("/User updated successfully!/", $response);
    $this->assertMatchesRegularExpression("/Yok/", $response);
  }

  public function test_destroy(): void
  {
    $_SERVER['REQUEST_METHOD'] = 'DELETE';

    $this->request = new Request();
    $this->request->addParams(['id' => $this->user->id]);

    $controller = new \App\Controllers\UsersController();
    ob_start();
    $controller->destroy($this->request);
    $response = ob_get_clean();

    $this->assertMatchesRegularExpression("/User deleted successfully/", $response);
    $this->assertNull(User::findById($this->user->id));
  }
}
