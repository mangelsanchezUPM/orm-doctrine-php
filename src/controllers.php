<?php

/**
 * ResultsDoctrine - controllers.php
 *
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

use MiW\Results\Entity\User;
use MiW\Results\Entity\Result;
use MiW\Results\Utility\DoctrineConnector;

function funcionHomePage()
{
    global $routes;

    $rutaListadoUsuario = $routes->get('ruta_user_list')->getPath();
    $rutaUsuario = $routes->get('ruta_user')->getPath();
    $rutaListadoResultados = $routes->get('ruta_result_list')->getPath();

    echo <<< ____MARCA_FIN
    <ul>
        <li><a href="$rutaListadoUsuario">Listado Usuarios</a></li>
        <li><a href="$rutaUsuario">Listado Resultados</a></li>
        <li><a href="$rutaListadoResultados">Listado Resultados</a></li>
    </ul>
____MARCA_FIN;
}

function funcionListadoUsuarios(): void
{
    $entityManager = DoctrineConnector::getEntityManager();

    $userRepository = $entityManager->getRepository(User::class);
    $users = $userRepository->findAll();
    var_dump($users);
}

function funcionUsuario(string $name)
{
    $entityManager = DoctrineConnector::getEntityManager();
    echo $name;
    $userRepository = $entityManager->getRepository(User::class);
    $user = $userRepository->findOneBy(['username' => $name]);
    var_dump($user);
}

function funcionBorrarUsuario(string $name)
{
    $entityManager = DoctrineConnector::getEntityManager();
    $userRepository = $entityManager->getRepository(User::class);
    $user = $userRepository->findBy(['username' => $name])[0];
    try {
        $entityManager->remove($user);
        $entityManager->flush();
        echo "Usuario borrado: " . PHP_EOL;
        var_dump($user);
    } catch (Throwable $exception) {
        echo $exception->getMessage() . PHP_EOL;
    }
}

function funcionResultados()
{
    $entityManager = DoctrineConnector::getEntityManager();

    $resultRepository = $entityManager->getRepository(Result::class);
    $results = $resultRepository->findAll();
    var_dump($results);
}

function funcionResultado(string $result)
{
    $entityManager = DoctrineConnector::getEntityManager();
    echo "$result";
    $resultRepository = $entityManager->getRepository(Result::class);
    $results = $resultRepository->findBy(['result' => $result]);
    var_dump($results);
}

function funcionBorrarResultado(string $name)
{
    $entityManager = DoctrineConnector::getEntityManager();
    $resultRepository = $entityManager->getRepository(Result::class);
    $result = $resultRepository->findOneBy(['result' => $name]);
    try {
        $entityManager->remove($result);
        $entityManager->flush();
        echo "Resultado borrado: " . PHP_EOL;
        var_dump($result);
    } catch (Throwable $exception) {
        echo $exception->getMessage() . PHP_EOL;
    }
}