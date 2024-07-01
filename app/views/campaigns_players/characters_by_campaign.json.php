<?php
/** @var $characters */

$charactersToJson = [];

foreach ($characters as $character) {
  $charactersToJson[] = [
    'id' => $character->id,
    'player_id' => $character->player_id,
    'name' => $character->name,
    'level' => $character->level,
    'gender' => $character->gender,
    'race' => $character->race,
    'klass' => $character->klass,
    'klass_level' => $character->klass_level,
    'hp' => $character->hp,
    'strength' => $character->strength,
    'dexterity' => $character->dexterity,
    'constitution' => $character->constitution,
    'intelligence' => $character->intelligence,
    'wisdom' => $character->wisdom,
    'charisma' => $character->charisma,
    'points_to_spend' => $character->points_to_spend,
    'skills' => $character->skills,
    'background' => $character->background
  ];
}

$json['characters'] = $charactersToJson;
