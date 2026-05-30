<?php
require_once dirname(__DIR__) . '/config/Conexion.php';
require_once dirname(__DIR__) . '/models/Usuario.php';

class AuthController {
    
    // Controlador para registrar un usuario
    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $database = new Conexion();
            $db = $database->getConnection();
            $usuario = new Usuario($db);

            $usuario->nombre = $_POST['nombre'];
            $usuario->email = $_POST['email'];
            $usuario->password = $_POST['password'];

            if ($usuario->registrar()) {
                // Redireccionar al login con un mensaje de éxito
                header("Location: index.php?action=login&success=Usuario registrado con éxito");
                exit();
            } else {
                header("Location: index.php?action=registro&error=El correo ya está registrado");
                exit();
            }
        }
    }

    // Controlador para iniciar sesión
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $database = new Conexion();
            $db = $database->getConnection();
            $usuario = new Usuario($db);

            $usuario->email = $_POST['email'];
            $usuario->password = $_POST['password'];

            if ($usuario->login()) {
                // Iniciar la sesión de PHP y guardar los datos del empleado
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['usuario_id'] = $usuario->id;
                $_SESSION['usuario_nombre'] = $usuario->nombre;

                // Redireccionar al tablero principal de tareas
                header("Location: index.php?action=tareas");
                exit();
            } else {
                header("Location: index.php?action=login&error=Credenciales incorrectas");
                exit();
            }
        }
    }

    // Cerrar sesión
    public function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header("Location: index.php?action=login");
        exit();
    }
}
?>