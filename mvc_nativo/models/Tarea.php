<?php
class Tarea {
    private $conn;
    private $table_name = "tasks"; // CORRECCIÓN: Apunta a la tabla 'tasks' de Laravel

    // Propiedades del objeto Tarea
    public $id;
    public $titulo;
    public $descripcion;
    public $estado;
    public $usuario_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. LEER: Obtener tareas usando 'user_id'
    public function leerPorUsuario($usuario_id) {
        // CORRECCIÓN: Cambiado 'usuario_id' por 'user_id' en la consulta SQL
        $query = "SELECT id, titulo, descripcion, estado, created_at 
                  FROM " . $this->table_name . " 
                  WHERE user_id = :usuario_id 
                  ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt;
    }

    // 2. CREAR: Guardar nueva tarea en la tabla 'tasks'
    public function crear() {
        // CORRECCIÓN: Ajustado a las columnas 'user_id' y sin especificar created_at/updated_at que Laravel llena solos o por default
        $query = "INSERT INTO " . $this->table_name . " 
                  SET titulo = :titulo, descripcion = :descripcion, estado = 'pendiente', user_id = :usuario_id";

        $stmt = $this->conn->prepare($query);

        $this->titulo = htmlspecialchars(strip_tags($this->titulo));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));

        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":usuario_id", $this->usuario_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // 3. ACTUALIZAR ESTADO (Para la funcionalidad AJAX)
    public function actualizarEstado() {
        // CORRECCIÓN: Cambiado 'usuario_id' por 'user_id'
        $query = "UPDATE " . $this->table_name . " 
                  SET estado = :estado 
                  WHERE id = :id AND user_id = :usuario_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":usuario_id", $this->usuario_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>