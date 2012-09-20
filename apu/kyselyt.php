<?php

class kyselyt {
    private $_pdo;
    
    private function valmistele($sqllause) {
        return $this->_pdo->prepare($sqllause);
    }
    
    public function tunnista($tunnus, $salasana) {
        $kysely = $this->valmistele('SELECT username FROM user WHERE username = ? AND password = ?');
        if ($kysely->execute(array($tunnus, $salasana))) {
            return $kysely->fetchObject();
        } else {
            return null;
        }
    }
    
    
}

?>
