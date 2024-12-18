<?php
session_start();
require '../models/DBModel.php';

class HandleWebFragmentContr
{
    private $db;

    public function __construct()
    {
        $this->db = new DBModel();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processRequest();
        }
    }

    private function processRequest()
    {
        try {
            $payload = file_get_contents('php://input');
            $dataArray = json_decode($payload, true);

            parse_str($dataArray['webAppData'], $output);
            $user = json_decode($output['user']);

            $existingUser = $this->db->getUser($user);
            if ($existingUser) {
                $_SESSION['id'] = $existingUser['id'];
                $existingUser['user_points'] = $this->db->getUserPoints($_SESSION['id']);
                JsonView::render($existingUser);
            }
        } catch (PDOException $e) {
            JsonView::render(['status' => 'failed', 'error' => $e->getMessage()]);
        }
    }
}

new HandleWebFragmentContr();
