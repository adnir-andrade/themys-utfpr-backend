<?php
/** @var $users */
/** @var $paginator */

$usersToJson = [];

foreach ($users as $user) {
  $usersToJson[] = [
    'id' => $user->id,
    'name' => $user->name,
    'username' => $user->username,
    'email' => $user->email,
    'role' => $user->role,
    'profile_url' => $user->profile_url];
}

$json['users'] = $usersToJson;
$json['pagination'] = [
  'page' => $paginator->getPage(),
  'per_page' => $paginator->perPage(),
  'total_of_pages' => $paginator->totalOfPages(),
  'total_of_registers' => $paginator->totalOfRegisters(),
  'total_of_registers_of_page' => $paginator->totalOfRegistersOfPage(),
];
