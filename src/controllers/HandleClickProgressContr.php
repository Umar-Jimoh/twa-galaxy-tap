<?php
session_start();
require '../models/DBModel.php';

class HandleClickProgressContr
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

            if ($dataArray['clicked']) {
                $newPoints = $this->db->updateUserPoints($_SESSION['id']); 
            }

            JsonView::render(['status' => 'success', 'points' => $newPoints]);
        } catch (PDOException $e) {
            JsonView::render(['status' => 'failed', 'error' => $e->getMessage()]);
        }
    }
}

new HandleClickProgressContr();
