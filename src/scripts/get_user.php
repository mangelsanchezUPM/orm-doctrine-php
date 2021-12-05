<?php

/**
 * src/scripts/get_user.php
 *
 * @category Scripts
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;
use MiW\Results\Utility\Utils;

if ($argc < 2 || $argc > 3 || !is_numeric($argv[1])) {
    $fich = basename(__FILE__);

    echo <<< MARCA_FIN

    Usage: $fich <UserID> [--json]

MARCA_FIN;
    exit(0);
}

// Carga las variables de entorno
Utils::loadEnv(dirname(__DIR__, 2));

$entityManager = DoctrineConnector::getEntityManager();

$userRepository = $entityManager->getRepository(User::class);
$user = $userRepository->find($argv[1]);

if (is_null($user)) {
    echo "User $argv[1] not found";
    exit(0);
}

if (in_array('--json', $argv, true)) {
    echo json_encode($user, JSON_PRETTY_PRINT);
} else {
    echo PHP_EOL . sprintf(
            '  %2s: %20s %30s %7s' . PHP_EOL,
            'Id', 'Username:', 'Email:', 'Enabled:'
        );
    /** @var User $user */
    echo sprintf(
        '- %2d: %20s %30s %7s',
        $user->getId(),
        $user->getUsername(),
        $user->getEmail(),
        ($user->isEnabled()) ? 'true' : 'false'
    ),
    PHP_EOL;
}
