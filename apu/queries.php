<?php

class Queries {

    private $_pdo;

    public function __construct($pdo) {
        $this->_pdo = $pdo;
    }

    private function valmistele($sqllause) {
        try {
            return $this->_pdo->prepare($sqllause);
        } catch (PDOException $e) {
            file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);
            die("ERROR: " . $e->getMessage());
        }
    }

    public function tunnista($tunnus, $salasana) {
        $kysely = $this->valmistele('SELECT username FROM users WHERE username = ? AND password = ?');
        if ($kysely->execute(array($tunnus, $salasana))) {
            return $kysely->fetchObject();
        } else {
            die("ERROR!");
            return null;
        }
    }


}

require_once 'connection.php';

$kyselija = new Queries(connect());
?>
