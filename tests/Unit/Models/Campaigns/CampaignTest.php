<?php

namespace Tests\Unit\Models\Campaigns;

use App\Models\Campaign;
use App\Models\User;
use Tests\TestCase;

class CampaignTest extends TestCase
{
  private User $user;
  private Campaign $campaign1;
  private Campaign $campaign2;

  public function setUp(): void
  {
    parent::setUp();

    $this->user = new User([
      'name' => 'User 1',
      'username' => 'user1',
      'email' => 'fulano@example.com',
      'password' => '123456',
      'password_confirmation' => '123456',
      'role' => 'player',
      'profile_url' => './assets/image/profile/anon.jpg'
    ]);
    $this->user->save();

    $this->campaign1 = new Campaign([
      'name' => 'Campaign test 1',
      'dm_id' => $this->user->id,
      'next_session' => '2024-06-30',
    ]);
    $this->campaign1->save();

    $this->campaign2 = new Campaign([
      'name' => 'Campaign test 2',
      'dm_id' => $this->user->id,
      'next_session' => '2024-07-30',
    ]);
    $this->campaign2->save();
  }



  public function test_should_create_new_campaign(): void
  {
    $this->assertCount(2, Campaign::all());
  }

  public function test_all_should_return_all_campaigns(): void
  {
    $campaigns[] = $this->campaign1->id;
    $campaigns[] = $this->campaign2->id;

    $all = array_map(fn ($campaigns) => $campaigns->id, Campaign::all());

    $this->assertCount(2, $all);
    $this->assertEquals($campaigns, $all);
  }

  public function test_destroy_should_remove_the_campaign(): void
  {
    $this->campaign1->destroy();
    $this->assertCount(1, Campaign::all());
  }

  public function test_set_id(): void
  {
    $this->campaign1->id = 10;
    $this->assertEquals(10, $this->campaign1->id);
  }

  public function test_set_name(): void
  {
    $this->campaign1->name = 'New Campaign Name';
    $this->assertEquals('New Campaign Name', $this->campaign1->name);
  }

  public function test_set_dm_id(): void
  {
    $newDmId = $this->user->id + 1;
    $this->campaign1->dm_id = $newDmId;
    $this->assertEquals($newDmId, $this->campaign1->dm_id);
  }

  public function test_set_next_session(): void
  {
    $newNextSession = '2024-08-30';
    $this->campaign1->next_session = $newNextSession;
    $this->assertEquals($newNextSession, $this->campaign1->next_session);
  }

  public function test_errors_should_return_errors(): void
  {
    $campaign = new Campaign();

    $this->assertFalse($campaign->isValid());
    $this->assertFalse($campaign->save());

    $this->assertEquals('não pode ser vazio!', $campaign->errors('dm_id'));
    $this->assertEquals('não pode ser vazio!', $campaign->errors('name'));
  }

  public function test_find_by_id_should_return_the_campaign(): void
  {
    $this->assertEquals($this->campaign1->id, Campaign::findById($this->campaign1->id)->id);
  }

  public function test_find_by_id_should_return_null(): void
  {
    $this->assertNull(Campaign::findById(3));
  }

  public function test_find_by_dm_id_should_return_campaigns(): void
  {
    $campaigns = Campaign::findByDmId($this->user->id);
    $this->assertCount(2, $campaigns);
    $this->assertEquals($this->campaign1->id, $campaigns[0]->id);
    $this->assertEquals($this->campaign2->id, $campaigns[1]->id);
  }

  public function test_find_by_dm_id_should_return_empty_array(): void
  {
    $this->assertEmpty(Campaign::findByDmId(999));
  }

  public function test_find_by_name_should_return_campaign(): void
  {
    $this->assertEquals($this->campaign1->id, Campaign::findByName('Campaign test 1')->id);
  }

  public function test_find_by_name_should_return_null(): void
  {
    $this->assertNull(Campaign::findByName('Non-existing Campaign'));
  }

  public function test_update_should_change_campaign_data(): void
  {
    $newName = 'Updated Campaign Name';
    $this->campaign1->name = $newName;
    $this->campaign1->save();

    $updatedCampaign = Campaign::findById($this->campaign1->id);
    $this->assertEquals($newName, $updatedCampaign->name);
  }

  public function test_update_should_change_next_session(): void
  {
    $newNextSession = '2024-08-30';
    $this->campaign1->next_session = $newNextSession;
    $this->campaign1->save();

    $updatedCampaign = Campaign::findById($this->campaign1->id);
    $this->assertEquals('2024-08-30', $updatedCampaign->next_session);
  }
}
