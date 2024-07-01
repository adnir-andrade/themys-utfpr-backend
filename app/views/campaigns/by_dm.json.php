<?php
/** @var $campaigns */

$campaignsToJson = [];

foreach ($campaigns as $campaign) {
  $campaignsToJson[] = [
    'id' => $campaign->id,
    'name' => $campaign->name,
    'next_session' => $campaign->next_session,
  ];
}

$json['campaigns'] = $campaignsToJson;
