<?php

require __DIR__ . '/../vendor/autoload.php';
require '../vendor/fw/libs/functions.php';

define('ROOT', dirname(__DIR__));
define('LIBS', dirname(__DIR__) . '/vendor/fw/libs');
define('TMP_FILES', dirname(__DIR__) . '/tmpfiles');
define('FILES', dirname(__DIR__) . '/public/files');

$db = require ROOT . '/config/config_db.php';
require LIBS . '/rb.php';

R::setup($db['dsn'], $db['user'], $db['pass']);
R::freeze(true);

$faker = Faker\Factory::create();

$user = R::dispense('users');

$user->login = "administrator";
$user->password = (new Hautelook\Phpass\PasswordHash(8, false))->HashPassword('superadmin');
$user->email = $faker->unique()->email;
$user->name = $faker->name;
$user->birthdate = $faker->date();
$user->photo = null;
$user->role = 'admin';

R::store($user);

$passwordHasher = new Hautelook\Phpass\PasswordHash(8, false);
$password = $passwordHasher->HashPassword('qwer1234');

for ($i = 0; $i < 150; $i++) {
    $user = R::dispense('users');

    $user->login = $faker->unique()->userName;
    $user->password = $password;
    $user->email = $faker->unique()->email;
    $user->name = $faker->name;
    $user->birthdate = $faker->date();
    $user->photo = null;
    $user->role = 'user';

    $userId = R::store($user);

    for ($j = 0; $j < 2; $j++) {
        $file = R::dispense('files');

        $file->user_id = $userId;
        $file->file = $faker->file(TMP_FILES, FILES, false);
        $file->name = $faker->sentence(rand(1, 6), true) . getExt($file->file);

        R::store($file);
    }
}
