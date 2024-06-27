<?php
/** @var $campaigns_players */
/** @var $paginator */

$campaignsPlayersToJson = [];

foreach ($campaigns_players as $cp) {
  $campaignsPlayersToJson[] = [
    'id' => $cp->id,
    'player_id' => $cp->player_id,
    'campaign_id' => $cp->campaign_id,
    'character_id' => $cp->character_id];
}

$json['campaigns_players'] = $campaignsPlayersToJson;
$json['pagination'] = [
  'page' => $paginator->getPage(),
  'per_page' => $paginator->perPage(),
  'total_of_pages' => $paginator->totalOfPages(),
  'total_of_registers' => $paginator->totalOfRegisters(),
  'total_of_registers_of_page' => $paginator->totalOfRegistersOfPage(),
];
