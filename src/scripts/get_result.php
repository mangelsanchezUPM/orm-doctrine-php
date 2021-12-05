<?php

/**
 * src/scripts/get_result.php
 *
 * @category Scripts
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use MiW\Results\Entity\Result;
use MiW\Results\Utility\DoctrineConnector;
use MiW\Results\Utility\Utils;

if ($argc < 2 || $argc > 3 || !is_numeric($argv[1])) {
    $fich = basename(__FILE__);

    echo <<< MARCA_FIN

    Usage: $fich <ResultID> [--json]

MARCA_FIN;
    exit(0);
}

// Carga las variables de entorno
Utils::loadEnv(dirname(__DIR__, 2));

$entityManager = DoctrineConnector::getEntityManager();

$resultsRepository = $entityManager->getRepository(Result::class);
$result = $resultsRepository->find($argv[1]);

if (is_null($result)) {
    echo "Result $argv[1] not found";
    exit(0);
}

if (in_array('--json', $argv, true)) {
    echo json_encode($result, JSON_PRETTY_PRINT);
} else {
    echo PHP_EOL . sprintf(
            '  %2s: %80s %30s %7s' . PHP_EOL,
            'Id', 'User:', 'Result:', 'Time:'
        );
    /* @var Result $result */
    echo sprintf(
        '- %2d: %80s %30s %7s',
        $result->getId(),
        $result->getUser(),
        $result->getResult(),
        $result->getTime()->format("c")
    ),
    PHP_EOL;
}
