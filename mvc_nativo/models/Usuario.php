<?php
class Usuario {
    private $conn;
    private $table_name = "users"; // Unificado con la tabla de Laravel

    public $id;
    public $nombre; // Mantenemos la propiedad como 'nombre' para no romper el controlador
    public $email;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    // REGISTRAR USUARIO (Mapeado a la tabla 'users')
    public function registrar() {
        $query = "INSERT INTO " . $this->table_name . " (name, email, password) VALUES (:nombre, :email, :password)";
        $stmt = $this->conn->prepare($query);

        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->email = htmlspecialchars(strip_tags($this->email));
        
        $password_encriptada = password_hash($this->password, PASSWORD_BCRYPT);

        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $password_encriptada);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // LOGIN DE USUARIO (Mapeado a la tabla 'users')
    public function login() {
        // CORRECCIÓN AQUÍ: Cambiado 'nombre' por 'name' en la consulta SQL
        $query = "SELECT id, name, password FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);

        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (password_verify($this->password, $row['password'])) {
                $this->id = $row['id'];
                $this->nombre = $row['name']; // CORRECCIÓN AQUÍ: Capturamos 'name' de la BD
                return true;
            }
        }
        return false;
    }
}
?>