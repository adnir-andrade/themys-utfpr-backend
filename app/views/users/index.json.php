<?php

$usersToJson = [];

/** @var \App\Models\User $users */
foreach ($users as $user) {
  $usersToJson[] = [
    'id' => $user->id,
    'name' => $user->name,
    'username' => $user->username,
    'email' => $user->email,
    'role' => $user->role];
}

$json['users'] = $usersToJson;
$json['pagination'] = [
  'page' => $paginator->getPage(),
  'per_page' => $paginator->perPage(),
  'total_of_pages' => $paginator->totalOfPages(),
  'total_of_registers' => $paginator->totalOfRegisters(),
  'total_of_registers_of_page' => $paginator->totalOfRegistersOfPage(),
];
