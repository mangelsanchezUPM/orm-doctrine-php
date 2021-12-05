<?php

/**
 * src/update_user.php
 *
 * @category Utils
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;
use MiW\Results\Utility\Utils;
if ($argc != 7 && !is_numeric($argv[1])) {
    $fich = basename(__FILE__);
    echo <<< MARCA_FIN

    Usage: $fich <UserID> <Username|.> <Email|.> <Password|.> <Enabled|.> <IsAdmin|.>
    The symbol '.' means that the field will not be updated.

MARCA_FIN;
    exit(0);
}

// Carga las variables de entorno
Utils::loadEnv(dirname(__DIR__, 2));

$entityManager = DoctrineConnector::getEntityManager();
$repository = $entityManager->getRepository(User::class);
$user = $repository->find($argv[1]);

if (is_null($user)) {
    echo "User with ID #" . $argv[1] . " not found";
    exit(0);
}

for ($i = 2; $i < $argc; $i++) {
    if ($argv[$i] != ".") {
        switch ($i) {
            case 2:
                $user->setUsername($argv[$i]);
                break;
            case 3:
                $user->setEmail($argv[$i]);
                break;
            case 4:
                $user->setPassword($argv[$i]);
                break;
            case 5:
                $user->setEnabled($argv[$i] == "true");
                break;
            case 6:
                $user->setIsAdmin($argv[$i] == "true");
                break;
        }
    }
}
try {
    $entityManager->persist($user);
    $entityManager->flush();
    echo 'Updated User with ID #' . $user->getId() . PHP_EOL;
} catch (Throwable $exception) {
    echo $exception->getMessage() . PHP_EOL;
}
