<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/Resources/Orm.php';
require __DIR__ . '/Resources/Users/User.php';
require __DIR__ . '/Resources/Users/UsersMapper.php';
require __DIR__ . '/Resources/Users/UsersRepository.php';
require __DIR__ . '/Resources/Users/UserType.php';

Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');
