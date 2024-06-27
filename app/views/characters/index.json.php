<?php
/** @var $characters */
/** @var $paginator */

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
    'background' => $character->background
  ];
}

$json['characters'] = $charactersToJson;
$json['pagination'] = [
  'page' => $paginator->getPage(),
  'per_page' => $paginator->perPage(),
  'total_of_pages' => $paginator->totalOfPages(),
  'total_of_registers' => $paginator->totalOfRegisters(),
  'total_of_registers_of_page' => $paginator->totalOfRegistersOfPage(),
];
