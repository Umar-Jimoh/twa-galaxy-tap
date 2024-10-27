<?php
require '../../config/DBConfig.php';
require '../view/JsonView.php';

class DBModel extends DBConfig
{
    private $dbInstance;
    protected $conn;
    private $connInfo;

    public function __construct()
    {
        $this->dbInstance = self::GetInstance();
        $this->conn = $this->dbInstance->getConnection();
        $this->connInfo = self::getConnectionInfo();
    }

    public function addUser($user)
    {
        if ($this->connInfo['status'] === 'success') {
            try {
                $sql = "INSERT INTO Users(id, username, first_name, language_code) VALUES (:id, :username, :first_name, :language_code)";
                $stmt = $this->conn->prepare($sql);

                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':username', $username, PDO::PARAM_STR);
                $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
                $stmt->bindParam(':language_code', $language_code, PDO::PARAM_STR);

                // Set value from the user object
                $id = $user->id;
                $username = $user->username;
                $first_name = $user->first_name;
                $language_code = $user->language_code;

                // $stmt->execute();
            } catch (PDOException $e) {
                JsonView::render(['status' => 'failed', 'error' => $e->getMessage()]);
            }
        } else {
            JsonView::render(['status' => 'failed', 'error' => $this->connInfo['error']]);
        }
    }
}
