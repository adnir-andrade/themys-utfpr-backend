# Themys - A TTRPG Assistent (PhP + React Native)

Themys Project is a Tabletop RPG assistent focused on providing players with direct access to essential information about their characters, campaigns, and especially their RPG system rules. The software also aims to allow the Dungeon Master to manage and configure essential aspects of the RPG system, providing utilities and automations that will help any DM to focus more on the campaign itself. The software is aimed at tabletop RPG players and Dungeon Masters who use the Dungeons & Dragons 5 (Next) rule system or homebrews.

## Links
- [Wiki](https://github.com/adnir-andrade/themys-utfpr-backend/wiki)
- [Backlog](https://github.com/users/adnir-andrade/projects/1/views/1)

## Installation

### Dependências

- Docker
- Docker Compose

### Getting Started

#### Clone Repository

```
$ git clone git@github.com:adnir-andrade/themys-utfpr-backend.git
$ cd themys-utfpr-backend
```

#### Define the env variables

```
$ cp .env.example .env
```

#### Install the dependencies

```
$ ./run composer install
```

#### Up the containers

```
$ docker compose up -d
```

ou

```
$ ./run up -d
```

#### Create database and tables

```
$ ./run db:reset
```

#### Populate database

```
$ ./run db:populate
```

### Fixed uploads folder permission

```
sudo chown www-data:www-data public/assets/uploads
```

#### Run the tests

```
$ docker compose run --rm php ./vendor/bin/phpunit tests --color
```

ou

```
$ ./run test
```

#### Run the linters

[PHPCS](https://github.com/PHPCSStandards/PHP_CodeSniffer/)

```
$ ./run phpcs
```

[PHPStan](https://phpstan.org/)

```
$ ./run phpstan
```

Access [localhost](http://localhost)

### Teste de API

```shell
curl -H "Accept: application/json" localhost/problems
```
