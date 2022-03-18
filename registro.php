<?php

if (isset($_POST)) {

    //conexion a la base de datos
    require_once 'includes/conexion.php';

        if(!isset($_SESSION)) {
            session_start();
        }

    //recoger los valores del formulario de registro
    $nombre = isset($_POST['nombre']) ? mysqli_real_escape_string($db, $_POST['nombre']) : false;
    $apellidos = isset($_POST['apellidos']) ? mysqli_real_escape_string($db, $_POST['apellidos']) : false;
    $email = isset($_POST['email']) ? mysqli_real_escape_string($db, trim($_POST['email'])) : false;
    $password = isset($_POST['password']) ? mysqli_real_escape_string($db, $_POST['password']) : false;

    //array de errores
    $errores = array();

    //validar datos antes de guardarlos en la base de datos
    if(!empty($nombre) && !is_numeric($nombre) && !preg_match('/[0-9]/', $nombre)) {
        $nombre_validado = true;
    }else {
        $nombre_validado = false;
        $errores['nombre']= 'El nombre es inválido';
    }

    if(!empty($apellidos) && !is_numeric($apellidos) && !preg_match('/[0-9]/', $apellidos)) {
        $apellidos_validado = true;
    }else {
        $apellidos_validado = false;
        $errores['apellidos']= 'El apellido es inválido';
    }

    if(!empty($email) && filter_var( $email, FILTER_VALIDATE_EMAIL)) {
        $email_validado = true;
    }else {
        $email_validado = false;
        $errores['email']= 'El email es inválido';
    }

    if(!empty($password)) {
        $password_validado = true;
    }else {
        $password_validado = false;
        $errores['password']= 'La contraseña está vadía';
    }

    $guardar_usuario = false;
    if(count($errores) == 0) {
        $guardar_usuario = true;

        //cifrar contraseña
        $password_segura = password_hash($password, PASSWORD_BCRYPT, ['cost' => 4]);

        //insertar usuario a la base de datos
        $sql = "INSERT INTO usuarios VALUES(null, '$nombre', '$apellidos', '$email', '$password_segura', CURDATE());";
        $guardar = mysqli_query($db, $sql);

        /*var_dump(mysqli_error($db));
        die();*/

        if($guardar) {
            $_SESSION['completado'] = 'El registro se ha completado con éxito';
        }else {
            $_SESSION['errores']['general'] = 'fallo al guardar el usuario';
        }

    }else {
        $_SESSION['errores'] = $errores;
        header('Location: index.php');
    }
}
header('Location: index.php');
