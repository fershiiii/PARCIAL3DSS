<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'controllers/AuthController.php';
// 1. DESCOMENTA O AGREGA ESTA LÍNEA AQUÍ ARRIBA:
require_once 'controllers/TareaController.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'login';

$authController = new AuthController();
// 2. INSTANCIA EL CONTROLADOR DE TAREAS:
$tareaController = new TareaController();

switch ($action) {
    case 'login':
        if (isset($_SESSION['usuario_id'])) { header("Location: index.php?action=tareas"); exit(); }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { $authController->login(); } else { include 'views/login.php'; }
        break;

    case 'registro':
        if (isset($_SESSION['usuario_id'])) { header("Location: index.php?action=tareas"); exit(); }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { $authController->registrar(); } else { include 'views/registro.php'; }
        break;

    case 'logout':
        $authController->logout();
        break;

    // 3. ACTUALIZA ESTOS DOS CASOS EN EL SWITCH:
    case 'tareas':
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login&error=Debes iniciar sesión");
            exit();
        }
        // Llama al método listar del controlador (él se encargará de incluir la vista)
        $tareaController->listar();
        break;

    case 'crear_tarea':
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        $tareaController->crear();
        break;

        case 'eliminar_tarea':
        if (!isset($_SESSION['usuario_id'])) { header("Location: index.php?action=login"); exit(); }
        $tareaController->eliminar();
        break;

        case 'actualizar_estado_ajax':
        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode(["status" => "error", "message" => "Sesión inválida"]);
            exit();
        }
        $tareaController->actualizarEstadoAjax();
        break;

    default:
        header("Location: index.php?action=login");
        break;
}
?>