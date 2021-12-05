<?php

/**
 * src/scripts/delete_result.php
 *
 * @category Scripts
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use MiW\Results\Entity\Result;
use MiW\Results\Utility\DoctrineConnector;
use MiW\Results\Utility\Utils;

if ($argc != 2 || !is_numeric($argv[1])) {
    $fich = basename(__FILE__);

    echo <<< MARCA_FIN

    Usage: $fich <ResultID>

MARCA_FIN;
    exit(0);
}

// Carga las variables de entorno
Utils::loadEnv(dirname(__DIR__, 2));

$entityManager = DoctrineConnector::getEntityManager();

$resultRepository = $entityManager->getRepository(Result::class);
$result = $resultRepository->find($argv[1]);

if (is_null($result)) {
    echo "Result with ID #" . $argv[1] . " not found";
    exit(0);
}

try {
    echo 'Deleted Result with ID #' . $result->getId() . PHP_EOL;
    $entityManager->remove($result);
    $entityManager->flush();
} catch (Throwable $exception) {
    echo $exception->getMessage() . PHP_EOL;
}
