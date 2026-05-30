<?php
class Conexion {
    private $host = "localhost";
    private $db_name = "dataaudit_labs";
    private $username = "root"; // El usuario por defecto en XAMPP
    private $password = "";     // La contraseña por defecto en XAMPP es vacía
    public $conn;

    public function getConnection() {
        $this->conn = null;
        
        try {
            // Creamos la conexión usando el driver PDO
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name, 
                $this->username, 
                $this->password
            );
            // Configurar PDO para que lance excepciones en caso de errores de SQL
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Forzar el juego de caracteres a UTF-8 para evitar problemas con tildes o eñes
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Error de conexión en la base de datos: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}
?>