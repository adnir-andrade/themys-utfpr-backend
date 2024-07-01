<?php
/** @var $players */

foreach ($players as $player) {
  $playersToJson[] = [
    'id' => $player->id,
    'name' => $player->name,
    'email'=> $player->email,
  ];
}

$json['players'] = $playersToJson;

echo json_encode($json);
