<?php

namespace Tests\Unit\Controllers;

use App\Models\Campaign;
use App\Models\User;
use Core\Http\Request;

class CampaignsControllerTest extends ControllerTestCase
{
  private User $user;
  private Campaign $campaign1;
  private Campaign $campaign2;

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

    $this->campaign1 = new Campaign([
      'name' => 'Campaign 1',
      'dm_id' => $this->user->id
    ]);
    $this->campaign1->save();

    $this->campaign2 = new Campaign([
      'name' => 'Campaign 2',
      'dm_id' => $this->user->id
    ]);
    $this->campaign2->save();
  }

  public function test_index(): void
  {
    $response = $this->get('index', 'App\Controllers\CampaignsController');

    $this->assertMatchesRegularExpression("/{$this->campaign1->name}/", $response);
    $this->assertMatchesRegularExpression("/{$this->campaign2->name}/", $response);
  }

  public function test_show(): void
  {
    $_GET['id'] = $this->campaign1->id;
    $response = $this->get('show', 'App\Controllers\CampaignsController');

    $this->assertMatchesRegularExpression("/{$this->campaign1->name}/", $response);
  }

  public function test_create(): void
  {
    $body = json_encode([
      'campaign' => [
        'name' => 'New Campaign',
        'dm_id' => $this->user->id
      ]
    ]);

    $this->request->setBody($body);

    $response = $this->post('create', 'App\Controllers\CampaignsController');

    $this->assertMatchesRegularExpression("/Campaign registered!/", $response);
    $this->assertMatchesRegularExpression("/New Campaign/", $response);
  }

  public function test_update(): void
  {
    $body = json_encode([
      'campaign' => [
        'name' => 'Updated Campaign'
      ]
    ]);

    $_SERVER['REQUEST_METHOD'] = 'PUT';

    $this->request = new Request();
    $this->request->setBody($body);

    $this->request->addParams(['id' => $this->campaign1->id]);

    $controller = new \App\Controllers\CampaignsController();
    ob_start();
    $controller->update($this->request);
    $response = ob_get_clean();

    $this->assertMatchesRegularExpression("/Campaign updated successfully!/", $response);
    $this->assertMatchesRegularExpression("/Updated Campaign/", $response);
  }

  public function test_destroy(): void
  {
    $_SERVER['REQUEST_METHOD'] = 'DELETE';

    $this->request = new Request();
    $this->request->addParams(['id' => $this->campaign1->id]);

    $controller = new \App\Controllers\CampaignsController();
    ob_start();
    $controller->destroy($this->request);
    $response = ob_get_clean();

    $this->assertMatchesRegularExpression("/Campaign deleted successfully/", $response);
    $this->assertNull(Campaign::findById($this->campaign1->id));
  }
}
