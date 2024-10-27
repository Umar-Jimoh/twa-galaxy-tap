<?php
require '../../config/dbconfig.php';
require '../view/jsonview.php';

class DB extends DBConnection
{
    public function __construct() {}

    public function addUser($user)
    {
        $dbInstance = self::GetInstance();
        $conn = $dbInstance->getConnection();
        $connInfo = self::getConnectionInfo();

        if ($connInfo['status'] === 'success') {
            try {
                $sql = "INSERT INTO Users(id, username, first_name, language_code) VALUES (:id, :username, :first_name, :language_code)";
                $stmt = $conn->prepare($sql);

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
            JsonView::render(['status' => 'failed', 'error' => $connInfo['error']]);
        }
    }
}
