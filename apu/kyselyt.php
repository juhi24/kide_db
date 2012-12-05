<?php

class kyselyt {

    private $_pdo;

    public function __construct($pdo) {
        $this->_pdo = $pdo;
    }

    private function valmistele($sqllause) {
        try {
        return $this->_pdo->prepare($sqllause);
        } catch (PDOException $e) {
            die("VIRHE: " . $e->getMessage());
        }
    }

    public function tunnista($tunnus, $salasana) {
        $kysely = $this->valmistele('SELECT username FROM users WHERE username = ? AND password = ?');
        if ($kysely->execute(array($tunnus, $salasana))) {
            return $kysely->fetchObject();
        } else {
            die("EITOIMI!");
            return null;
        }
    }

}

require_once './yhteys.php';

$kyselija = new Kyselyt(yhdista());
?>
