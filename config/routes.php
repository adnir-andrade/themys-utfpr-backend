<?php

use App\Controllers\AuthenticationsController;
use App\Controllers\UsersController;
use App\Controllers\CampaignsController;
use App\Controllers\CampaignsPlayersController;
use App\Controllers\CharactersController;
use App\Controllers\ProfileController;
use Core\Router\Route;

// Authentication
Route::post('/login', [AuthenticationsController::class, 'authenticate'])->name('users.authenticate');
Route::post('/logout', [AuthenticationsController::class, 'destroy'])->name('users.logout');

Route::middleware('auth')->group(function () {
  // Logout
  Route::get('/logout', [AuthenticationsController::class, 'destroy'])->name('users.logout');

  // Profile
  Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
  Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');

  // Users
  Route::get('/users', [UsersController::class, 'index'])->name('users.index');
  Route::get('/users/{id}', [UsersController::class, 'show'])->name('users.show');
  Route::post('/users', [UsersController::class, 'create'])->name('users.create');
  Route::put('/users/{id}', [UsersController::class, 'update'])->name('users.update');
  Route::delete('/users/{id}', [UsersController::class, 'destroy'])->name('users.destroy');

  // Campaings
  Route::get('/campaigns', [CampaignsController::class, 'index'])->name('campaigns.index');
  Route::get('/campaigns/{id}', [CampaignsController::class, 'show'])->name('campaigns.show');
  Route::post('/campaigns', [CampaignsController::class, 'create'])->name('campaigns.create');
  Route::put('/campaigns/{id}', [CampaignsController::class, 'update'])->name('campaigns.update');
  Route::delete('/campaigns/{id}', [CampaignsController::class, 'destroy'])->name('campaigns.destroy');

  // CampaingsPlayers
  Route::get('/campaigns_players', [CampaignsPlayersController::class, 'index'])->name('campaigns_players.index');
  Route::get('/campaigns_players/{id}', [CampaignsPlayersController::class, 'show'])->name('campaigns_players.show');
  Route::post('/campaigns_players', [CampaignsPlayersController::class, 'create'])->name('campaigns_players.create');
  Route::put('/campaigns_players/{id}', [CampaignsPlayersController::class, 'update'])->name('campaigns_players.update');
  Route::delete('/campaigns_players/{id}', [CampaignsPlayersController::class, 'destroy'])->name('campaigns_players.destroy');
  Route::get('/campaigns_players/players_by_campaign/{id}', [CampaignsPlayersController::class, 'playersByCampaignId'])->name('campaigns_players.players_by_campaign');
  Route::get('/campaigns_players/characters_by_campaign/{id}', [CampaignsPlayersController::class, 'charactersByCampaignId'])->name('campaigns_players.characters_by_campaign');
  Route::get('/campaigns_players/campaigns_by_player/{id}', [CampaignsPlayersController::class, 'campaignsByPlayerId'])->name('campaigns_players.campaigns_by_player');
  Route::get('/campaigns_players/campaign_by_character/{id}', [CampaignsPlayersController::class, 'campaignByCharacterId'])->name('campaigns_players.campaign_by_character');

  // CharacterPlayers
  Route::get('/characters', [CharactersController::class, 'index'])->name('characters.index');
  Route::get('/characters/{id}', [CharactersController::class, 'show'])->name('characters.show');
  Route::post('/characters', [CharactersController::class, 'create'])->name('characters.create');
  Route::put('/characters/{id}', [CharactersController::class, 'update'])->name('characters.update');
  Route::delete('/characters/{id}', [CharactersController::class, 'destroy'])->name('characters.destroy');
  Route::get('/characters/player/{player_id}', [CharactersController::class, 'getCharactersByPlayer'])->name('characters.getCharactersByPlayer');
});
