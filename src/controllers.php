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
        <li><a href="$rutaListadoUsuario">Gestión de Usuarios</a></li>
        <li><a href="$rutaListadoResultados">Gestión de Resultados</a></li>
    </ul>
____MARCA_FIN;
}

function volverInicio() {
    global $routes;

    $ruta_inicio = $routes->get('ruta_raíz')->getPath();
    echo "<hr>";
    echo "<a href='$ruta_inicio'>Volver a inicio</a>";
}

function funcionListadoUsuarios(): void
{
    $entityManager = DoctrineConnector::getEntityManager();

    $userRepository = $entityManager->getRepository(User::class);
    $users = $userRepository->findAll();
    echo "<table>
            <tr>
                <th><label>Nombre de usuario</label></th>
                <th><label>Email</label></th>
                <th><label>Habilitado</label></th>
                <th><label>Es Admin</label></th>
                <th colspan='3'><label>Operaciones</label></th>
            </tr>";
    foreach ($users as $user) {
        $username = $user->getUsername();
        $email = $user->getEmail();
        $enabled = $user->isEnabled();
        $isAdmin = $user->isAdmin();
        $url = '/users/' . urlencode($username);

        echo <<< ____MARCA_FIN
                <tr>
                    <td><label>$username</label></td>
                    <td><label>$email</label></td> 
                    <td><label>$enabled</label></td> 
                    <td><label>$isAdmin</label></td> 
                    <td><a href="$url">Ver Detalle</a></td>
                    <td><a href="$url/delete">Eliminar</a></td>
                    <td><a href="$url/update-form">Modificar</a></td>
                </tr>
    ____MARCA_FIN;
    }
    global $routes;

    $rutaFormCrearUsuario = $routes->get('ruta_create_user_form')->getPath();
    echo "</table>";
    echo "<a href='$rutaFormCrearUsuario'>Insertar nuevo usuario</a>";
    volverInicio();
}

function funcionUsuario(string $name)
{
    $entityManager = DoctrineConnector::getEntityManager();
    echo $name;
    $userRepository = $entityManager->getRepository(User::class);
    $user = $userRepository->findOneBy(['username' => $name]);
    var_dump($user);
    volverInicio();
}

function formCrearUsuario()
{
    global $routes;
    $rutaFormCrearUsuario = $routes->get('ruta_create_user')->getPath();

    echo <<< ____MARCA_FIN
    
    <form method="post" action="$rutaFormCrearUsuario">
        <h2>Creación de usuario</h2>
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
    volverInicio();

}

function funcionCrearUsuario()
{
    $entityManager = DoctrineConnector::getEntityManager();
    insertarUsuario($entityManager, new User());
    volverInicio();

}

function formModificarUsuario(string $name)
{
    $usernameEncoded = urlencode($name);
    $rutaModificarUsuario = "/users/$usernameEncoded/update";

    $entityManager = DoctrineConnector::getEntityManager();
    $userRepository = $entityManager->getRepository(User::class);
    $user = $userRepository->findOneBy(['username' => $name]);

    $username = $user->getUsername();
    $email = $user->getEmail();
    $enabled = $user->isEnabled() ? "checked" : "";
    $isAdmin = $user->isAdmin() ? "checked" : "";

    echo <<< ____MARCA_FIN
    <form method="post" action="$rutaModificarUsuario">
        <h2>Modificacion de usuario</h2>
        <table>
            <tr><td><label>Nombre de usuario: </label></td> 
                <td><input type="text" name="username" value="$username" required></td></tr>
            <tr><td><label>Email: </label></td> 
                <td><input type="email" name="email" value="$email" required></td></tr>
            <tr><td><label>Contraseña: </label></td>
                <td><input type="password" name="password" required></td></tr>                
            <tr><td><label>Habilitado: </label></td> 
                <td><input type='checkbox' name='enabled' $enabled></td></tr>
            <tr><td><label>Es Admin: </label></td> 
                <td><input type='checkbox' name='isAdmin'" $isAdmin></td></tr>
            <tr><td colspan='2'><button type='submit'>Actualizar</button></td></tr>
        </table>
    </form>
____MARCA_FIN;
    volverInicio();

}


function funcionModificarUsuario(string $name)
{
    $entityManager = DoctrineConnector::getEntityManager();
    $userRepository = $entityManager->getRepository(User::class);
    $user = $userRepository->findOneBy(['username' => $name]);
    insertarUsuario($entityManager, $user);
    volverInicio();

}

function insertarUsuario($entityManager, User $newUser): void
{
    if (isset($_POST) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        $newUser->setUsername($_POST['username']);
        $newUser->setEmail($_POST['email']);
        $newUser->setPassword($_POST['password']);
        $newUser->setEnabled(false);
        $newUser->setIsAdmin(false);

        if (isset($_POST['enabled'])) {
            $newUser->setEnabled(true);
        }
        if (isset($_POST['isAdmin'])) {
            $newUser->setIsAdmin(true);
        }
        try {
            $entityManager->persist($newUser);
            $entityManager->flush();
            var_dump($newUser);

        } catch (Throwable $exception) {
            echo $exception->getMessage() . PHP_EOL;
        }
    } else {
        echo "Usuario no modificado";
    }
}

function funcionBorrarUsuario(string $name)
{
    $entityManager = DoctrineConnector::getEntityManager();
    $userRepository = $entityManager->getRepository(User::class);
    $user = $userRepository->findOneBy(['username' => $name]);
    try {
        $entityManager->remove($user);
        $entityManager->flush();
        echo "Usuario borrado: " . PHP_EOL;
        var_dump($user);
    } catch (Throwable $exception) {
        echo $exception->getMessage() . PHP_EOL;
    }
    volverInicio();

}

function funcionResultados()
{
    $entityManager = DoctrineConnector::getEntityManager();

    $resultRepository = $entityManager->getRepository(Result::class);
    $results = $resultRepository->findAll();
    var_dump($results);
    volverInicio();

}

function funcionResultado(string $result)
{
    $entityManager = DoctrineConnector::getEntityManager();
    echo "$result";
    $resultRepository = $entityManager->getRepository(Result::class);
    $results = $resultRepository->findBy(['result' => $result]);
    var_dump($results);
    volverInicio();

}

function formCrearResultado()
{
    global $routes;
    $rutaFormCrearUsuario = $routes->get('ruta_create_user')->getPath();

    echo <<< ____MARCA_FIN
    
    <form method="post" action="$rutaFormCrearUsuario">
        <h2>Creación de usuario</h2>
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