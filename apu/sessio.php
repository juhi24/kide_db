<?php

class Sessio {

  public function __construct() {
    session_start();
  }

  public function __set($avain, $arvo) {
    $_SESSION[$avain] = $arvo;
  }

  public function __get($avain) {
    if ($this->__isset($avain)) {
      return $_SESSION[$avain];
    }
    return null;
  }

  public function __isset($avain) {
    return isset($_SESSION[$avain]);
  }

  public function __unset($avain) {
    unset($_SESSION[$avain]);
  }

}

$sessio = new Sessio();

?>