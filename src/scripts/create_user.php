<?php

/**
 * src/create_user.php
 *
 * @category Utils
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;
use MiW\Results\Utility\Utils;

if ($argc < 4 || $argc > 6) {
    $fich = basename(__FILE__);
    echo <<< MARCA_FIN

    Usage: $fich <Username> <Email> <Password> [<Enabled>] [<IsAdmin>]
    Values for Enabled and IsAdmin are considered true only if "true" is assigned.

MARCA_FIN;
    exit(0);
}

// Carga las variables de entorno
Utils::loadEnv(dirname(__DIR__, 2));

$entityManager = DoctrineConnector::getEntityManager();

$user = new User();
$user->setUsername($argv[1]);
$user->setEmail($argv[2]);
$user->setPassword($argv[3]);
$user->setEnabled(true);
$user->setIsAdmin(false);

if ($argc == 5) {
    $user->setEnabled($argv[4] == "true");
}
if ($argc == 6) {
    $user->setIsAdmin($argv[5] == "true");
}

try {
    $entityManager->persist($user);
    $entityManager->flush();
    echo 'Created User with ID #' . $user->getId() . PHP_EOL;
} catch (Throwable $exception) {
    echo $exception->getMessage() . PHP_EOL;
}
