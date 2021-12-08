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
    $rutaFormCrearUsuario = $routes->get('ruta_create_user_form')->getPath();
    $rutaListadoResultados = $routes->get('ruta_result_list')->getPath();

    echo <<< ____MARCA_FIN
    <ul>
        <li><a href="$rutaListadoUsuario">Listado Usuarios</a></li>
        <li><a href="$rutaFormCrearUsuario">Crear Usuario</a></li>
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

function formCrearUsuario()
{
    global $routes;
    $rutaFormCrearUsuario = $routes->get('ruta_create_user')->getPath();

    echo <<< ____MARCA_FIN
    
    <form method="post" action="$rutaFormCrearUsuario">
        <table>
            <tr><td><label>Nombre de usuario: </label></td> 
                <td><input type="text" name="username" required></td></tr>
            <tr><td><label>Email: </label></td> 
                <td><input type="email" name="email" required></td></tr>
            <tr><td><label>Contraseña: </label></td>
                <td><input type="password" name="password" required></td></tr>
            <tr><td><label>Habilitado: </label></td> 
                <td><input type="checkbox" name="enabled" checked></td></tr>
            <tr><td><label>Es Admin: </label></td> 
                <td><input type="checkbox" name="isAdmin"></td></tr>
            <tr><td colspan="2"><button type="submit">Crear</button></td></tr>
        </table>
    </form>
____MARCA_FIN;
}

function funcionCrearUsuario()
{
    $newUser = new User();
    if (isset($_POST)) {
        if (isset($_POST['username'])) {
            $newUser->setUsername($_POST['username']);
        }
        if (isset($_POST['email'])) {
            $newUser->setEmail($_POST['email']);
        }
        if (isset($_POST['password'])) {
            $newUser->setPassword($_POST['password']);
        }
        if (isset($_POST['enabled'])) {
            $newUser->setEnabled(true);
        }
        if (isset($_POST['isAdmin'])) {
            $newUser->setIsAdmin(true);
        }
        $entityManager = DoctrineConnector::getEntityManager();
        try {
            $entityManager->persist($newUser);
            $entityManager->flush();
            var_dump($newUser);
        } catch (Throwable $exception) {
            echo $exception->getMessage() . PHP_EOL;
        }
    } else {
        echo "Usuario no generado";
    }
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