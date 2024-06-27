<?php
/** @var $campaigns */
/** @var $paginator */

$campaignsToJson = [];

foreach ($campaigns as $campaign) {
  $campaignsToJson[] = [
    'id' => $campaign->id,
    'dm_id' => $campaign->dm_id,
    'name' => $campaign->name,
    'next_session' => $campaign->next_session];
}

$json['campaigns'] = $campaignsToJson;
$json['pagination'] = [
  'page' => $paginator->getPage(),
  'per_page' => $paginator->perPage(),
  'total_of_pages' => $paginator->totalOfPages(),
  'total_of_registers' => $paginator->totalOfRegisters(),
  'total_of_registers_of_page' => $paginator->totalOfRegistersOfPage(),
];
