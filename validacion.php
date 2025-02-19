<?php
session_start();
include 'conexion.php'; // Se realiza lo que es  la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    $IDUsuario = trim($_POST['IDUsuario']);
    $Password = trim($_POST['Password']);

    if (!empty($IDUsuario) && !empty($Password)) {
        try {
            $pdo = new PDO("mysql:host=localhost:3309;dbname=cliente", "root", "", [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);

            $stmt = $pdo->prepare("SELECT * FROM usuario WHERE  idusuarios = :idUsuario");
            $stmt->bindParam(':idUsuario', $IDUsuario, PDO::PARAM_STR);
            $stmt->execute();
            $usuario = $stmt->fetch();
            
            var_dump($usuario);
            var_dump($usuario['contrasenia']);

            if ($IDUsuario == $usuario['idusuarios'] && $Password == $usuario['contrasenia']) {
                $_SESSION['usuario'] = [
                    'idusuarios' => $usuario['IDUsuario'],
                    'nombre' => $usuario['Nombre'],
                    'apellidoPaterno' => $usuario['ApellidoPaterno'],
                    'apellidoMaterno' => $usuario['ApellidoMaterno'],
                    'rol' => $usuario['TipoUsuario']
                ];
              
                header("Location: formulario.php");

            } else {
                echo "<h3>Usuario no registrado</h3>";
            }
        } catch (PDOException $e) {
            echo "<h3>Error de conexión: " . $e->getMessage() . "</h3>";
        }
    } else {
        echo "<h3>Por favor, complete todos los campos.</h3>";
    }
}
?>