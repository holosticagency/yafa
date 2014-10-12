<?php
namespace holisticagency\yafa\core;

use holisticagency\yafa;

/**
 * @author Panajotis Zamos <aqw1137@gmail.com>
 * @since		Version 0.0.1
 */
class Session extends PreObject {
  const SESSION_STARTED = TRUE;
  const SESSION_NOT_STARTED = FALSE;
  const MAX_IDLE_TIME = 1800; // 30min

  // The state of the session
  private $sessionState = self::SESSION_NOT_STARTED;
  // THE only instance of the class
  private static $instance;

  private function __construct() {}

  /**
   *    Returns THE instance of 'Session'.
   *    The session is automatically initialized if it wasn't.
   *
   *    @return    object
   * */
  public static function getInstance() {
    if (!isset(self::$instance)) {
      self::$instance = new self;

      self::$instance->startSession();
      self::$instance->checkIpIntegrity();
      self::$instance->checkIfExpired();
    }

    return self::$instance;
  }

  /**
   *    (Re)starts the session.
   *
   *    @return    bool    TRUE if the session has been initialized, else FALSE.
   * */
  public function startSession() {
    if ($this->sessionState == self::SESSION_NOT_STARTED) {
      $this->sessionState = session_start();
      session_regenerate_id();
      $_SESSION['REMOTE_ADDR'] = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:'NO_IP';
    }

    return $this->sessionState;
  }

  /**
   *    Check if client IP has changed. If it did, session will be destroyed.
   *
   *    @return    void
   * */
  public function checkIpIntegrity() {
    if ($this->sessionState == self::SESSION_STARTED) {
      if(!isset($_SESSION['REMOTE_ADDR']) || $_SESSION['REMOTE_ADDR'] != $_SERVER['REMOTE_ADDR']){
        self::$instance->destroy();
      }
    }
  }

  public function checkIfExpired(){
    if (!isset($_SESSION['timeout_idle'])) {
      $_SESSION['timeout_idle'] = time() + self::MAX_IDLE_TIME;
    } else {
      if ($_SESSION['timeout_idle'] < time()) {
        self::$instance->destroy();
      } else {
        $_SESSION['timeout_idle'] = time() + self::MAX_IDLE_TIME;
      }
    }
  }
  /**
   *    Stores datas in the session.
   *    Example: $instance->foo = 'bar';
   *
   *    @param    name    Name of the datas.
   *    @param    value    Your datas.
   *    @return    void
   * */
  public function __set($name, $value) {
    $_SESSION[$name] = $value;
  }

  /**
   *    Gets datas from the session.
   *    Example: echo $instance->foo;
   *
   *    @param    name    Name of the datas to get.
   *    @return    mixed    Datas stored in session.
   * */
  public function __get($name) {
    if (isset($_SESSION[$name])) {
      return $_SESSION[$name];
    }
  }

  public function __isset($name) {
    return isset($_SESSION[$name]);
  }

  public function __unset($name) {
    unset($_SESSION[$name]);
  }

  /**
   *    Destroys the current session.
   *
   *    @return    bool    TRUE is session has been deleted, else FALSE.
   * */
  public function destroy() {
    if ($this->sessionState == self::SESSION_STARTED) {
      $this->sessionState = !session_destroy();
      $_SESSION = array();
      return!$this->sessionState;
    }
    return FALSE;
  }

}