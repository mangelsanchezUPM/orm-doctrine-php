<?php

/**
 * src/delete_user.php
 *
 * @category Utils
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;
use MiW\Results\Utility\Utils;

if ($argc != 2 || !is_numeric($argv[1])) {
    $fich = basename(__FILE__);

    echo <<< MARCA_FIN

    Usage: $fich <UserID>

MARCA_FIN;
    exit(0);
}

// Carga las variables de entorno
Utils::loadEnv(dirname(__DIR__, 2));
$entityManager = DoctrineConnector::getEntityManager();

$userRepository = $entityManager->getRepository(User::class);
$user = $userRepository->find($argv[1]);

if (is_null($user)) {
    echo "User with ID #" . $argv[1] . " not found";
    exit(0);
}

try {
    echo 'Deleted User with ID #' . $user->getId() . PHP_EOL;
    $entityManager->remove($user);
    $entityManager->flush();
} catch (Throwable $exception) {
    echo $exception->getMessage() . PHP_EOL;
}
