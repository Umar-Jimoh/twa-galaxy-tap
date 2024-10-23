<?php
require '../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

class DBConnection
{
    private static $instance;
    private $servername;
    private $username;
    private $password;
    private $dbname;
    protected $conn;
    private static $connInfo;

    private function __construct()
    {
        $this->servername = $_ENV["DB_HOST"];
        $this->username = $_ENV["DB_USERNAME"];
        $this->password = $_ENV["DB_PASSWORD"];
        $this->dbname = $_ENV["DB_NAME"];
        self::$connInfo = ['status' => null, 'error' => null];

        try {
            $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$connInfo = ['status' => 'success', 'error' => null];
        } catch (PDOException $e) {
            self::$connInfo = ['status' => 'failed', 'error' => $e->getMessage()];
        }
    }

    public static function GetInstance()
    {
        if (self::$instance === null) {
            self::$instance = new DBConnection();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public static function getConnectionInfo()
    {
        return self::$connInfo;
    }

    function __clone() {}

    function __wakeup() {}
}
