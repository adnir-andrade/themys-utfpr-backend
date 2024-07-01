<?php
/** @var $campaigns */

foreach ($campaigns as $campaign) {
  $campaignsToJson[] = [
    'id' => $campaign->id,
    'name' => $campaign->name,
    'next_session'=> $campaign->next_session,
  ];
}

$json['campaigns'] = $campaignsToJson;

echo json_encode($json);
