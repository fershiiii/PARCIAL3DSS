<?php
require_once dirname(__DIR__) . '/config/Conexion.php';
require_once dirname(__DIR__) . '/models/Tarea.php';

class TareaController {


    // 1. LISTAR TAREAS (Para mostrarlas en la tabla)
    public function listar() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $database = new Conexion();
        $db = $database->getConnection();
        $tareaModel = new Tarea($db);

        // Pasamos el ID del usuario logueado para que SOLO vea sus tareas
        $usuario_id = $_SESSION['usuario_id'];
        $stmt = $tareaModel->leerPorUsuario($usuario_id);
        
        // Convertimos el resultado de la BD en un array fácil de recorrer
        $listadoTareas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Incluimos la vista mandándole los datos reales
        include dirname(__DIR__) . '/views/tareas.php';
    }

    // 2. CREAR TAREA
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            $database = new Conexion();
            $db = $database->getConnection();
            $tareaModel = new Tarea($db);

            // Llenamos las propiedades del modelo con los datos del formulario
            $tareaModel->titulo = $_POST['titulo'];
            $tareaModel->descripcion = $_POST['descripcion'];
            $tareaModel->usuario_id = $_SESSION['usuario_id'];

            if ($tareaModel->crear()) {
                header("Location: index.php?action=tareas&success=Tarea guardada con éxito");
                exit();
            } else {
                header("Location: index.php?action=tareas&error=No se pudo guardar la tarea");
                exit();
            }
        }
    }

    // Método para eliminar una tarea de forma tradicional
    public function eliminar() {
        if (session_status() == PHP_SESSION_NONE) { session_start(); }

        if (isset($_GET['id'])) {
            $database = new Conexion();
            $db = $database->getConnection();
            
            // Reutilizamos de forma astuta el modelo Tarea que ya mapea a 'tasks'
            $query = "DELETE FROM tasks WHERE id = :id AND user_id = :user_id";
            $stmt = $db->prepare($query);
            
            $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $_SESSION['usuario_id'], PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("Location: index.php?action=tareas&success=Tarea eliminada con éxito");
            } else {
                header("Location: index.php?action=tareas&error=No se pudo eliminar");
            }
            exit();
        }
    }

    public function actualizarEstadoAjax() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (session_status() == PHP_SESSION_NONE) { session_start(); }

            $database = new Conexion();
            $db = $database->getConnection();
            $tareaModel = new Tarea($db);

            // Mapeamos los datos que vienen por POST desde JavaScript
            $tareaModel->id = $_POST['id'];
            $tareaModel->estado = $_POST['estado'];
            $tareaModel->usuario_id = $_SESSION['usuario_id']; // Por seguridad

            // Forzamos a que el navegador sepa que responderemos JSON puro
            header('Content-Type: application/json');

            if ($tareaModel->actualizarEstado()) {
                echo json_encode(["status" => "success"]);
            } else {
                echo json_encode(["status" => "error", "message" => "No se pudo actualizar en la BD"]);
            }
            exit(); // 🚫 SÚPER CRÍTICO: Detiene a PHP para que no renderice HTML extra
        }
    }
}
?>