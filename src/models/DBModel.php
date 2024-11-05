<?php
require '../../config/DBConfig.php';
require '../views/JsonView.php';

class DBModel extends DBConfig
{
    private $dbInstance;
    protected $conn;
    private $connInfo;
    private $userInfo;
    private $userPoints;

    public function __construct()
    {
        $this->dbInstance = self::GetInstance();
        $this->conn = $this->dbInstance->getConnection();
        $this->connInfo = self::getConnectionInfo();
    }

    public function getUser($user)
    {
        if ($this->connInfo['status'] === 'success') {
            try {
                $sql = "SELECT * FROM Users WHERE id = :id";
                $stmt = $this->conn->prepare($sql);

                $id = $user->id;
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();

                $this->userInfo = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($this->userInfo) {
                    return $this->userInfo;
                } else {
                    return $this->addUser($user);
                }
            } catch (PDOException $e) {
                JsonView::render(['status' => 'failed', 'error' => $e->getMessage()]);
            }
        } else {
            JsonView::render(['status' => 'failed', 'error' => $this->connInfo['error']]);
        }
    }

    public function addUser($user)
    {
        if ($this->connInfo['status'] === 'success') {
            try {
                $sql = "INSERT INTO Users(id, username, first_name, language_code) VALUES (:id, :username, :first_name, :language_code)";
                $stmt = $this->conn->prepare($sql);

                // Set value from the user object
                $id = $user->id;
                $username = $user->username;
                $first_name = $user->first_name;
                $language_code = $user->language_code;

                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':username', $username, PDO::PARAM_STR);
                $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
                $stmt->bindParam(':language_code', $language_code, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    return $this->getUser($user);
                }
            } catch (PDOException $e) {
                JsonView::render(['status' => 'failed', 'error' => $e->getMessage()]);
            }
        } else {
            JsonView::render(['status' => 'failed', 'error' => $this->connInfo['error']]);
        }
    }

    public function getUserPoints($id)
    {
        if ($this->connInfo['status'] === 'success') {
            try {
                $sql = "SELECT points FROM User_Points WHERE id = :id";
                $stmt = $this->conn->prepare($sql);

                $id = $id;
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();

                $this->userPoints = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($this->userPoints) {
                    return $this->userPoints;
                } else {
                    $this->addUserPoints($id);
                }
            } catch (PDOException $e) {
                JsonView::render(['status' => 'failed', 'error' => $e->getMessage()]);
            }
        } else {
            JsonView::render(['status' => 'failed', 'error' => $this->connInfo['error']]);
        }
    }

    public function addUserPoints($id)
    {
        if ($this->connInfo['status'] === 'success') {
            try {
                $sql = "INSERT INTO User_Points (id) VALUES(:id)";
                $stmt = $this->conn->prepare($sql);

                $id = $id;
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    return $this->getUserPoints($id);
                }
            } catch (PDOException $e) {
                JsonView::render(['status' => 'failed', 'error' => $e->getMessage()]);
            }
        } else {
            JsonView::render(['status' => 'failed', 'error' => $this->connInfo['error']]);
        }
    }
}
