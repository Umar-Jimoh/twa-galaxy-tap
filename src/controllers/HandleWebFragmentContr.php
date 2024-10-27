<?php
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

            $this->db->addUser($user);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'failed', 'error' => $e->getMessage()]);
        }
    }
}

new HandleWebFragmentContr();
