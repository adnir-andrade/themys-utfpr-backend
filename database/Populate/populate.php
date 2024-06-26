<?php

require __DIR__ . '/../../config/bootstrap.php';

use Core\Database\Database;
use Database\Populate\CampaignsPopulate;
use Database\Populate\UsersPopulate;
use Database\Populate\CampaignsPlayersPopulate;
use Database\Populate\CharactersPopulate;

Database::migrate();

UsersPopulate::populate(12, 3);
CampaignsPopulate::populate(3);
CampaignsPlayersPopulate::populate(4);
CharactersPopulate::populate();
