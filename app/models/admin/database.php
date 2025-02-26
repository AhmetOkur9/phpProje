<?php 
if(!defined("MASTERPAGE")){
	die("Error");
};
?>
<?php
class Database {
    private $host = "127.0.0.1"; // Veritabanı sunucu adresi
    private $db = "deneme"; // Veritabanı adı
    private $username = "root"; // Veritabanı kullanıcı adı
    private $password = ""; // Veritabanı şifresi
    private $port = 3308; // Port numarası
    private $conn;

    public function __construct() {
        try {
            // PDO bağlantısı oluşturuluyor, port doğrudan dsn içinde tanımlanacak
            $dsn = "mysql:host=$this->host;dbname=$this->db;port=$this->port";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Veritabanı bağlantı hatası: " . $e->getMessage());
        }
    }

    public function connect() {
        return $this->conn;
    }

    // Veritabanı bağlantısını kapat
    public function close() {
        $this->conn = null;
    }
}
?>