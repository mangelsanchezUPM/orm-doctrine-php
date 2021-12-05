<?php

/**
 * src/update_result.php
 *
 * @category Utils
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use MiW\Results\Entity\Result;
use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;
use MiW\Results\Utility\Utils;

if ($argc < 3 || $argc > 4 && is_numeric($argv[1])) {
    $fich = basename(__FILE__);
    echo <<< MARCA_FIN

    Usage: $fich <ResultID> <Result|.> <UserID|.> [<Timestamp|.>]
    The symbol '.' means that the field will not be updated.

MARCA_FIN;
    exit(0);
}

// Carga las variables de entorno
Utils::loadEnv(dirname(__DIR__, 2));

$entityManager = DoctrineConnector::getEntityManager();
$repository = $entityManager->getRepository(Result::class);
$result = $repository->find($argv[1]);

if (is_null($result)) {
    echo "Result with ID #" . $argv[1] . " not found";
    exit(0);
}

for ($i = 2; $i < $argc; $i++) {
    if ($argv[$i] != ".") {
        switch ($i) {
            case 2:
                $result->setResult($argv[$i]);
                break;
            case 3:
                $result->setUser($entityManager
                    ->getRepository(User::class)
                    ->find($argv[$i]));
                break;
            case 4:
                $result->set($argv[$i]);
                break;
        }
    }
}
try {
    $entityManager->persist($result);
    $entityManager->flush();
    echo 'Updated Result with ID #' . $result->getId() . PHP_EOL;
} catch (Throwable $exception) {
    echo $exception->getMessage() . PHP_EOL;
}
