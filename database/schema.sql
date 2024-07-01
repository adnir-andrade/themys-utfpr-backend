SET foreign_key_checks = 0;

DROP TABLE IF EXISTS users;

CREATE TABLE users
(
  id                 int AUTO_INCREMENT PRIMARY KEY,
  name               varchar(255)        NOT NULL,
  username           varchar(255) UNIQUE NOT NULL,
  email              varchar(255)        NOT NULL,
  encrypted_password VARCHAR(255)        NOT NULL,
  role               varchar(255)        NOT NULL,
  profile_url        varchar(2000) NOT NULL DEFAULT './assets/image/profile/anon.jpg'
);

DROP TABLE IF EXISTS campaigns;

CREATE TABLE campaigns
(
  id           int AUTO_INCREMENT PRIMARY KEY,
  dm_id        int          NOT NULL,
  name         varchar(255) NOT NULL,
  next_session date,
  FOREIGN KEY (dm_id) REFERENCES users (id) ON DELETE CASCADE
);

DROP TABLE IF EXISTS campaigns_players;

CREATE TABLE campaigns_players
(
  id           int AUTO_INCREMENT PRIMARY KEY,
  player_id    int NOT NULL,
  campaign_id  int NOT NULL,
  character_id int,
  FOREIGN KEY (player_id) REFERENCES users (id) ON DELETE CASCADE,
  FOREIGN KEY (campaign_id) REFERENCES campaigns (id) ON DELETE CASCADE,
  FOREIGN KEY (character_id) REFERENCES characters (id) ON DELETE CASCADE
);

DROP TABLE IF EXISTS characters;

CREATE TABLE characters
(
  id           int AUTO_INCREMENT PRIMARY KEY,
  player_id    int          NOT NULL,
  name         varchar(255) NOT NULL,
  level        int          NOT NULL DEFAULT 1,
  gender       varchar(255),
  race         varchar(255) NOT NULL,
  klass        varchar(255) NOT NULL,
  klass_level  int          NOT NULL DEFAULT 1,
  hp           int          NOT NULL,
  strength     int          NOT NULL DEFAULT 10,
  dexterity    int          NOT NULL DEFAULT 10,
  constitution int          NOT NULL DEFAULT 10,
  intelligence int          NOT NULL DEFAULT 10,
  wisdom       int          NOT NULL DEFAULT 10,
  charisma     int          NOT NULL DEFAULT 10,
  points_to_spend int       NOT NULL DEFAULT 27,
  skills JSON         NOT NULL,
  background   TEXT,
  FOREIGN KEY (player_id) REFERENCES users (id) ON DELETE CASCADE
);

DROP TABLE IF EXISTS profile_images;

CREATE TABLE profile_images
(
  id        int AUTO_INCREMENT PRIMARY KEY,
  url       varchar(255) NOT NULL DEFAULT './assets/image/profile/anon.jpg',
  entity_id int          NOT NULL,
  context   varchar(10)  NOT NULL,
  constraint unique (entity_id, context)
);

SET foreign_key_checks = 1;
