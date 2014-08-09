<?php

namespace yafa\core;

/**
 * yafa Classes is collection of classes essential for yafa
 *
 * @package yafa_core
 * @version 0.0.4
 * @author panajotis zamos [aqw137@gmail.com]
 * @copyright	Copyright (c) 2011-2013, PLZ, Inc.
 * @since Version 0.0.1
 * <ul>
 *   <li>$Revision$</li>
 *   <li>$Date$</li>
 *   <li>$Id$</li>
 *   <li>$Author$</li>
 * </ul>
 */
class _yafa_core_ {
  // dummy class used just for displaying info above
}

/**
 * currently not in use
 * 
 * @since		Version 0.0.1
 */
abstract class PreObject {
  private $_messages = array();

  function setMessages($messages) {
    $this->_messages = $messages;
  }

  function addMessage($message) {
    $this->_messages[] = $message;
  }

  function getMessages() {
    return $this->_messages;
  }

  function getLastMessage() {
    return end($this->_messages);
  }
  
  function getMessagesCount() {
    return count($this->_messages);
  }
  
  function resetMessages(){
    $this->setMessages(array());
  }

  
}

/**
 * currently not in use
 * 
 * @since		Version 0.0.1
 */
abstract class Object extends PreObject {

}

/**
 * Container for various yafa constants
 * 
 * @since Version 0.0.5
 */
abstract class YAFA {
  /**
   * config key for use in cache
   */
  const CONFIG_CACHE_KEY = '__CONFIG__';
  /**
   * config key for use in cache
   */
  const CONTROLLER_UNKNOWN_ACTION = 'unknown_action';
  const CONTROLLER_MISSING_ACTION = 'missing_action';

  const DATA_ = '';
}

/**
 * Container for application works with {@see run()}
 * 
 * @since	 Version 0.0.4
 */
final class Application extends Object {

  function run() {
    //dbg(yafa_debug()->info());
    //yapi()->test()->getContent();
    
    if (PHP_SAPI === 'cli'){ 
      $this->_cli_app();
    }else{
      $this->_web_app();
    }

    //dbg(yafa_debug()->info());
  }
  
  private function _web_app(){
    yafa_loader()->loadAppClasses(); // try classes in app dir
    yafa_loader()->loadVendorClasses(); // than try classes in vendor dir
    yafa_loader()->loadYafaClasses(); // and finaly try in yafa dir

    yafa_holder()->hold('cache', new Cache());
    // check if requested resource is in cache
    if (yafa_responder()->respondeFromCache()) {
      return;
    }

    // is session needed before cache and responder?
    if(isset($_SERVER['REMOTE_ADDR'])){
      yafa_holder()->hold('session', Session::getInstance());
    }
    
    yafa_holder()->hold('db', new Db());
    yafa_holder()->hold('data', new Data());
    yafa_holder()->hold('config', new Config());

    yafa_holder()->hold('user', new User());
    yafa_loader()->missingClassHandler(); // handl errors for missing classes
    yafa_holder()->hold('common', new \yafa\Common());

    yafa_holder()->hold('router', new \yafa\core\Router());
    yafa_router()->engage();
    
    yafa_responder()->responde();
    
  }
  private function _cli_app(){
    yafa_loader()->loadAppClasses(); // try classes in app dir
    yafa_loader()->loadVendorClasses(); // than try classes in vendor dir
    yafa_loader()->loadYafaClasses(); // and finaly try in yafa dir

    yafa_holder()->hold('cache', new Cache());
//    // check if requested resource is in cache
//    if (yafa_responder()->respondeFromCache()) {
//      return;
//    }
//
//    // is session needed before cache and responder?
//    if(isset($_SERVER['REMOTE_ADDR'])){
//      yafa_holder()->hold('session', Session::getInstance());
//    }
    
    yafa_holder()->hold('db', new Db());
    yafa_holder()->hold('data', new Data());
    yafa_holder()->hold('config', new Config());

//    yafa_holder()->hold('user', new User());
    yafa_loader()->missingClassHandler(); // handl errors for missing classes
    yafa_holder()->hold('common', new \yafa\Common());

    yafa_holder()->hold('router', new \yafa\core\Router());
    yafa_router()->engage();
    
    yafa_responder()->responde();
    
    
  }

}

/**
 * @since	 Version 0.0.1
 */
class Router extends Object {

  private $_controller = null;

  function engage() {

    $this->_load_controller();
    $status = yafa_controller()->controll();

    if ($status) {
      $dbg = '';
      switch ($status) {
        case YAFA::CONTROLLER_UNKNOWN_ACTION:
          $dbg = __('Unknown controller action.');
          break;
        case YAFA::CONTROLLER_MISSING_ACTION:
          $this->_missing_controller_action();
          $sec_status = yafa_controller()->controll();
          if ($sec_status) {
            $dbg = __('Missing controller action: %s', yafa_controller()->getLastMessage());
          }
          break;
        default: $dbg = __('Unknown controller error.');
      }
      if ($dbg) {
        //dbg($dbg);
        trigger_error($dbg, \E_USER_WARNING);
      }
    }
  }

  /**
   * load controller using ctrl slug set in controller_slug
   */
  private function _set_controller(\yafa\interfaces\YafaController $controller) {
    yafa_holder()->hold('controller', $controller);
  }

  /**
   * reload controller using ctrl slug set in controller_slug
   */
  private function _reset_controller(\yafa\interfaces\YafaController $controller) {
    yafa_holder()->replace('controller', $controller);
  }

  private function _load_controller() {
    if (yafa_requester()->getControllerSlug() == '') {
      $ctrl_slug = yafa_config()->get('default=>controller');
    } else {
      $ctrl_slug = yafa_requester()->getControllerSlug();
    }
    if(isset($ctrl_slug)){
      $ctrl_slug = ucfirst($ctrl_slug);
      yafa_requester()->setControllerSlug($ctrl_slug);
      $ctrl_name = '\\mvc\\controller\\' . $ctrl_slug;
      $controller = new $ctrl_name();
    }else{
      $controller = new \yafa\default_classes\PageController();
    }

    $this->_set_controller($controller);
    return;
  }

  private function _missing_controller_action() {
    $ctrl_slug = ucfirst(yafa_requester()->getControllerSlug());
    $ctrl_name = '\\yafa\\mvc\\controller\\' . $ctrl_slug;
    $controller = new $ctrl_name();
    $this->_reset_controller($controller);
    return;
  }

  function getController() {
    return $this->_controller;
  }

  private function test() {

    echo '<h3>Engaged :)</h3>';

    // yafa_common()->generateRandStr(22) will trigger warning in this moment cos yafa common is not loaded
    //echo yafa_common()->generateRandStr(22);
  }

  private function neki_test0() {
    echo '<br>1. pozvano iz routera::test2 ' . yafa_config()->get('test');
    yafa_config()->set('test', 'setovano iz router::test');
    // $this->config DEPRECATED  echo '<br>pozvano iz routera::test ' . $this->config->get();
    echo '<br>2. pozvano iz routera::test2 ' . yafa_config()->get('test');

    $user = new User();
    echo '<br>3. pozvano iz neki test ' . yafa_config()->set('test', ': setovano iz neki_test()');
    echo '<br>4. pozvano iz usera ' . $user->get_config();
    echo '<br>5. pozvano iz usera ' . $user->set_config(': setovano iz usera');
    echo '<br>6. pozvano iz usera ' . $user->get_config();
    echo '<br>7. pozvano iz neki test ' . yafa_config()->get('test');
    // yafa_common()->generateRandStr(22) will trigger warning in this moment cos yafa common is not loaded
    //echo yafa_common()->generateRandStr(22);
  }

  function neki_test(interfaces\YafaController $ctrl) {


    $test = new \mvc\controller\Test();
    $test->index();
    echo $test->closuretest();

    echo "\n\n<br><br>\n";


    echo '<h2>extra :D </h2>';
    echo '<pre>';
  }

}

/**
 * Abstract layer to DB
 * 
 * <p>
 * how to use:
 * <code>
 *     yafa_data()->request($query);
 *     $responde = yafa_data()->responde();
 *     if($responde['status']){
 *       // error ocured, see $responde['message']
 *     }else{
 *       // status OK (it's 0)
 *     }
 * </code>
 * </p>
 * 
 * @package yafa_core
 * @uses Db
 * @since 0.0.6
 */
class Data extends Object {
  const ASD = '__CONFIG__';

  private $_request = array();
  private $_type = '';
  private $_query = array();
  private $_responde = array();
  private $_status = 0;
  private $_message = 'ok';
  private $_data = null;

  /**
   * Request for data
   * 
   * Type of requests:
   * <ul>
   *   <li>set</li>
   *   <li>begin</li>
   *   <li>commit</li>
   *   <li>rollback</li>
   *   <li>select</li>
   *   <li>insert</li>
   *   <li>update</li>
   *   <li>delete</li>
   *   <li>custom_sql</li>
   * </ul>
   * @param string $type
   * @param mixed $query used for select, insert, update, delete, cusom_sql
   * @return 0 on success
   */
  function request($type, $query = null) {
    $this->_type = $type;
    $this->_query = $query;

    // execute request
    $this->_exec();

    return $this->_status;
  }

  /**
   * Get date responde (from data request)
   * 
   * Responde is array with 3 keys;<br>
   * <ul>
   *   <li>status</li>
   *   <li>message</li>
   *   <li>data</li>
   * </ul>
   * 
   * responde status list:
   * <ul>
   *   <li>0 OK </li>
   *   <li>1 general error </li>
   *   <li>2 invalid request </li>
   * </ul>
   * @return type 
   */
  function responde() {
    $this->_responde['status'] = $this->_status;
    $this->_responde['message'] = $this->_message;
    $this->_responde['data'] = $this->_data;
    return $this->_responde;
  }

  private function _exec() {
    switch ($this->_type) {
      case 'select' : $this->_select();
        break;

      default :
        $this->_status = 1;
        $this->_message = __('Invalid request type.');
    }
  }

  private function _select() {
    $q = \hat\dal\DAL::query();

    if (isset($this->_query['_s'])) {
      $s = '';
      $sss = array();
      foreach ($this->_query['_s'] as $k => $v) {
        $v_parts = explode(',', $v);
        $ssa = array();
        foreach ($v_parts as $v_part) {
          $ssa[] = $k . '.' . $v_part;
        }
        $sss[] = implode(', ', $ssa);
      }
      $s = implode(', ', $sss);
      //dbg($s);
      $q = $q->select($s);
    }
    if (isset($this->_query['_f'])) {
      $q = $q->from($this->_query['_f']);
    }
    if (isset($this->_query['_lj'])) {
      foreach ($this->_query['_lj'] as $k => $v) {
        if (isset($v['_l'])) {
          $q = $q->leftJoin($v['_l']);
        }
      }
    }
    if (isset($this->_query['_w'])) {
      $q = $q->setWhereCondition($this->_query['_w']);
    }

//    $q = \hat\dal\DAL::query();
//    $q->select('i.*, ic.*, sc.*')
//            ->from('TestItem i')
//            ->leftJoin('i.TestItemCategories ic')
//            ->leftJoin('ic.TestCategorySubcategories sc')
//            ->where('i.id =?', 2)
//    ;
//    
    //dbg($q->queryDebug(1));
    $r = $q->queryStmt();
    if ($r === false) {
      $r = $q->getLastPdoErrorMessage();
      $this->_message = $r;
    } else {
      $r = $q->getResults();
      $this->_responde['count'] = \count($r);
      $this->_data = $r;
    }

    //dbg($r);
  }

  /**
   * @deprecated 0.0.6
   */
  function query() {
    //yafa_cache()->set($key, $value);
  }

}

/**
 * Get and set various config parameters
 * 
 * Merge client, tenant, domain, group and user config parameters and 
 * provide methods to set and get them. 
 * 
 * @uses Cache
 * @todo implement set(client|tenant|domain|group|user) variations
 * @since		Version 0.0.1
 */
class Config extends PreObject {

  private $_config = array();

  /*
   * merge client, tenant, domain, group and user config
   */

  public function __construct() {
//    $this->_config = yafa_cache()->getA(YAFA::CONFIG_CACHE_KEY);
//    if (empty($this->_config)) {
//      // retrive config from db
//    }
    global $config;
    $this->_config = $config;
  }

  private function _save() {
    // save config to db and 
    // update cache
  }

  /**
   * 
   * @param string $key parameter name
   * @param mixed $value parameter value
   * @return mixed parameter value
   */
  function set($key, $value) {
//    $this->_config[$key] = $value;
//    $this->_save();
//    return $value;
  }

  function getAll() {
    return $this->_config;
  }
  /**
   *
   * @param string $key parameter name
   * @return mixed parameter value or NULL if not found 
   */
  function get($key) {
    $config_delimiter = '=>';
    $key_parts = explode($config_delimiter, $key);
    $_config = $this->_config;
    $_value = null;
    foreach($key_parts as $key_part){
      $key_part = \trim($key_part);
      if (isset($_config[$key_part])) {
        $_config = $_config[$key_part];
      }else{
        return null;
      }
    }
    return $_config;
  }

}

/**
 * Get information for used (in current request) and installed (available) languages.
 * 
 * @since		Version 0.0.1
 */
class Language extends PreObject {
  //put your code here
}

/**
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

/**
 * @todo add on/off funcionality (start/stop/status)
 * @todo add method doc
 * 
 * @since		Version 0.0.1
 */
class Cache extends PreObject {

  private $_server;
  private $_ok = false;
  private $_params = array('host' => '127.0.0.1', 'port' => 6379, 'ttl' => 0, 'method' => 'connect'); // type: connect OR pconnect [p for persistent]
  private $_pre_prefix = 'YAFA_';
  private $_prefix = '';

  final public function __construct() {
    $this->_server = new \Redis();
    $r = $this->_server->connect($this->_params['host'], $this->_params['port'], $this->_params['ttl']);
    if ($r === false) {
      dbg('Error connecting to Redis server!');
      return false;
    }
    $this->_prefix = $this->_pre_prefix . HOST . '_';
    $this->_ok = true;
    //$this->_server->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_IGBINARY);
    return true;
  }

  /**
   * @example yafa_cache()->on()
   * @return void
   */
  function on() {
    $this->_ok = true;
  }

  function off() {
    $this->_ok = false;
  }

  function state() {
    return $this->_ok;
  }

  function set($key, $value, $ttl = 0) {
    $key = $this->_prefix . $key;
    if ($ttl) {
      $return = $this->_server->setex($key, $ttl, $value);
    } else {
      $return = $this->_server->set($key, $value);
    }
    return $return;
  }

  function get($key) {
    $key = $this->_prefix . $key;
    return $this->_server->get($key);
  }

  function setA($key, $value, $ttl = 0) {
    return $this->set($key, serialize($value), $ttl);
    //return $this->set($key, \igbinary_serialize($value), $ttl);
  }

  function getA($key) {
    return unserialize($this->get($key));
    //return \igbinary_unserialize($this->get($key));
  }

  function del($key) {
    $key = $this->_prefix . $key;
    return $this->_server->delete($key);
  }

  function clear() {
    return $this->_server->flushAll();
  }

  function info() {
    return $this->_server->info();
  }

}

/**
 * @since		Version 0.0.1
 */
class Db extends PreObject {

  protected $_dbh = null;
  protected $_fetch_type = \PDO::FETCH_ASSOC;
  protected $_sql = '';
  protected $pdo_error_message = array();
  protected $row_results = array();
  protected $results = array();

  final public function __construct() {
    global $dbh;
    if (isset($dbh)) {
      $this->_dbh = $dbh;
    }
    \hat\dal\DAL::setDbh($this->_dbh);
//    \hat\dal\DAL::setTableNamespace('\\yafa\\tables\\');
//    \hat\dal\DAL::setTablePath(YAFA_SYS_PATH . 'files/tables/');

    \hat\dal\DAL::setTableNamespace('\\holag\\tables\\');
    \hat\dal\DAL::setTablePath(YAFA_APP_PATH . 'files/tables/');

  }

  /**
   * dummy method to ensure Db is constructed
   */
  function init() {
    return true;
  }

  function hdm_test() {
    $q = \hat\dal\DAL::query();
    $q->select('i.*, ic.*, sc.*')
            ->from('TestItem i')
            ->leftJoin('i.TestItemCategories ic')
            ->leftJoin('ic.TestCategorySubcategories sc')
            ->where('i.id =?', 2)
    ;
    dbg($q->queryDebug(1));
    $r = $q->queryStmt();
    if ($r) {
      $r = $q->getResults();
    } else {
      $r = $q->getLastPdoErrorMessage();
    }

    dbg($r);
  }

  function getRowResults() {
    return $this->row_results;
  }

  function query2($q) {
    dbg($q);
  }

  function query($sql, $map = null) {
    $this->_sql = $sql;
    try {
      $results = $this->_dbh->query($this->_sql, $this->_fetch_type);
    } catch (\Exception $e) {
      $this->pdo_error_message[] = $e->getMessage();
      return false;
    }

    if ($results) {
      $this->row_results = $results->fetchAll($this->_fetch_type);
      return $this->_map($map);
      //return \count($this->row_results);
    } else {
      $err = $this->_dbh->errorInfo();
      if (isset($err[2])) {
        $this->pdo_error_message[] = $err[2];
      } else {
        $this->pdo_error_message[] = 'error with query';
      }

      echo "========= \n";
      var_dump($results);
      print_r($this->_sql);
      print_r($this->_dbh->errorInfo());

      return false;
    }
  }

  private function _map($map) {
    $delimiter = '__';
    if (!isset($map)) {
      return $this->row_results;
    }
    dbg($map);

    $results = $this->row_results;
    if (empty($results)) {
      return array();
    }
    if (isset($results[0])) {
      $first_result = $results[0];
    } else {
      return false;
    }
    $result_keys = array_keys($first_result);
    $result_map = array();
    foreach ($result_keys as $result_key) {
      $key_parts = explode($delimiter, $result_key);
      if (isset($key_parts[0]) && isset($key_parts[1])) {
        if (!isset($result_map[$key_parts[0]])) {
          $result_map[$key_parts[0]] = array();
        }
        $result_map[$key_parts[0]][] = $key_parts[1];
      } else {
        return false;
      }
    }

    dbg($result_map);

    return $results;
  }

  function test() {
    $sql = "select i.id as i__id, i.name as i__name, ic.item_id as ic__item_id, ic.category_id as ic__category_id, cs.category_id as cs__category_id, cs.subcategory_id as cs__subcategory_id   from test_item i left join test_item_category ic on i.id=ic.item_id left join test_category_subcategory cs on ic.category_id=cs.category_id where i.id = 2;";
    //$sql = "select i.id as i__id, i.name as i__name from test_item i where i.id = 2;";
    $map = array('i' => array('name' => 'Item', 'hm' => array('ic' => array('name' => 'Category', 'hm' => array('cs' => array('name' => 'SubCategory'))))));


    $query_result = yafa_db()->query($sql, $map);
    dbg($query_result);

    $r_str = <<<EOF
 <pre>
 i__id | i__name  | ic__item_id | ic__category_id | cs__category_id | cs__subcategory_id 
-------+----------+-------------+-----------------+-----------------+-------------------- 
     2 | item two |           2 |               1 |               1 |                  1 
     2 | item two |           2 |               2 |               2 |                  2 
     2 | item two |           2 |               2 |               2 |                  3 
     2 | item two |           2 |               3 |               3 |                  3 
(4 rows) </pre>
EOF;

    //result should by:
    $items = array(
      0 => array(
        'id' => 2,
        'name' => 'item two',
        'ic__' => array(
          0 => array(
            'item_id' => 2,
            'category_id' => 1,
            'cs__' => array(
              0 => array(
                'category_id' => 1,
                'subcategory_id' => 1
              )
            )
          ),
          1 => array(
            'item_id' => 2,
            'category_id' => 2,
            'cs__' => array(
              0 => array(
                'category_id' => 2,
                'subcategory_id' => 2
              ),
              1 => array(
                'category_id' => 2,
                'subcategory_id' => 3
              )
            )
          ),
          2 => array(
            'item_id' => 2,
            'category_id' => 3,
            'cs__' => array(
              0 => array(
                'category_id' => 3,
                'subcategory_id' => 3
              )
            )
          ),
        )
      )
    );

    dbg($r_str);
    dbg($items);
  }

}

/**
 * @since		Version 0.0.1
 */
class User extends Object {

  //put your code here
  function set_config($a) {
    yafa_config()->set('test', $a);
    return " user::SET($a)";
  }

  function get_config() {
    return yafa_config()->get('test');
  }

}

/**
 * @since		Version 0.0.2
 */
class Dummy extends Object {

  // used if calling class not loaded
  public function __call($method_name, $arguments) {
    //dbgx('FAIL!!!');
  }

}

/**
 * @since		Version 0.0.4
 */
final class Requester extends Object {

  private $_uri = '';
  private $_request = '';
  private $_uri_separator = '/';
  private $_uri_assoc_separator = ':';
  private $_cli_separator = '/';
  private $_cli_assoc_separator = ':';
  private $_request_separator = '/';
  private $_request_assoc_separator = ':';
  private $_uri_args = array();
  private $_request_args = array();
  private $_resource_args = array();
  private $_resource_assoc_args = array();
  private $_cli_script_name = '';
  private $_cli_args = array();
  private $_language_slug = '';
  private $_collection_slug = '';
  private $_controller_slug = '';
  private $_resource_slug = '';
  private $_resource_extension = '';
  private $_request_type = '';
  private $_format = '';
  private $_parameters = array();
  private $_body;
  private $_browser = array();
  private $_language = null;

  function __construct() {
//    dbg($_GET);
//    dbgx($_SERVER);
    if (PHP_SAPI === 'cli'){ 
      $this->_parse_cli_params();
    }else{
      $this->_parse_uri_params();
      $this->_parse_incoming_params();
      $this->_load_language();
    }
  }

  /**
   * 
   */
  function info() {
    $info = array(
      'uri' => $this->_uri,
      'uri_separator' => $this->_uri_separator,
      'uri_assoc_separator' => $this->_uri_assoc_separator,
      'cli_separator' => $this->_cli_separator,
      'cli_assoc_separator' => $this->_cli_assoc_separator,
      'request_separator' => $this->_request_separator,
      'request_assoc_separator' => $this->_request_assoc_separator,
      'uri_args' => $this->_uri_args,
      'request' => $this->_request,
      'request_args' => $this->_request_args,
      'cli_script_name' => $this->_cli_script_name,
      'cli_args' => $this->_cli_args,
      'language_slug' => $this->_language_slug,
      'collection_slug' => $this->_collection_slug,
      'controller_slug' => $this->_controller_slug,
      'resource_slug' => $this->_resource_slug,
      'resource_extension' => $this->_resource_extension,
      'resource_args' => $this->_resource_args,
      'resource_assoc_args' => $this->_resource_assoc_args,
      'request_type' => $this->_request_type,
      'format' => $this->_format,
      'parameters' => $this->_parameters,
      'body' => $this->_body,
      '_POST' => $_POST,
    );

    return $info;
  }

  private function _parse_incoming_params() {
    if(!isset($_SERVER['REQUEST_METHOD'])){
      return;
    }
    $parameters = array();

    $this->_request_type = \strtolower($_SERVER['REQUEST_METHOD']);
    // first of all, pull the GET vars
    if (isset($_SERVER['QUERY_STRING'])) {
      parse_str($_SERVER['QUERY_STRING'], $parameters);
    }

    // now how about PUT/POST bodies? These override what we got from GET
    $body = file_get_contents("php://input");
    $this->_body = $body;
    
   
    $content_type = false;
    if (isset($_SERVER['CONTENT_TYPE'])) {
      $content_type = $_SERVER['CONTENT_TYPE'];
    }
    switch ($content_type) {
      case "application/json":
        $body_params = json_decode($body);
        if ($body_params) {
          foreach ($body_params as $param_name => $param_value) {
            $parameters[$param_name] = $param_value;
          }
        }
        $this->_format = "json";
        break;
      case "application/x-www-form-urlencoded":
        parse_str($body, $postvars);
        foreach ($postvars as $field => $value) {
          $parameters[$field] = $value;
        }
        $this->_format = "html";
        break;
      default:
        // we could parse other supported formats here
        break;
    }
    $this->_parameters = $parameters;
    if (empty($this->_resource_extension)) {
      $this->_resource_extension = $this->_format;
    }
  }

  private function _parse_cli_params() {
    if(!isset($_SERVER['argv'])){
      return;
    }
    if(count($_SERVER['argv']) > 1){
      $this->_cli_args = $_SERVER['argv'];
      $this->_cli_script_name = array_shift($this->_cli_args);
    }else{
      $this->_cli_script_name = $_SERVER['argv'][0];
    }

    $this->_request_separator = $this->_cli_separator;
    $this->_request_assoc_separator = $this->_cli_assoc_separator;
    if(isset($this->_cli_args[0])){
      $this->_request_args = \explode($this->_cli_separator, $this->_cli_args[0]);
    }
    $this->_parse_request();
  }
  private function _parse_uri_params() {
    if(!isset($_SERVER['REQUEST_URI'])){
      return;
    }
    $this->_uri = \trim($_SERVER['REQUEST_URI'], $this->_uri_separator);
    $this->_uri_args = \explode($this->_uri_separator, $this->_uri);
    $this->_request = isset($_GET['q']) ? $_GET['q'] : $this->_uri;
    $this->_request = \trim($this->_request, $this->_uri_separator);
    $this->_request_args = \explode($this->_uri_separator, $this->_request);
    $this->_parse_request();
  }
  
  private function _parse_request(){
    //$custom_controllers = yafa_config()->get('custom_controllers');
    // Not holding config in this moment, get custom controllers manually
    $_custom_controllers = array('test');
    global $custom_controllers;
    if (!isset($custom_controllers)) {
      $custom_controllers = array();
    }
    $custom_controllers = array_merge($_custom_controllers, $custom_controllers);
    //dbg($custom_controllers);

    $i = 0;
    $leng_slug_len = 2;
    $ctrl_slug_len = 3;
    if (isset($this->_request_args[$i])) {
      if (\strlen($this->_request_args[$i]) == $leng_slug_len) {
        // it's language slug
        $this->_language_slug = $this->_request_args[$i];
        $i++;
      }
    }
    if (isset($this->_request_args[$i])) {
      if (\strlen($this->_request_args[$i]) == $ctrl_slug_len || \in_array($this->_request_args[$i], $custom_controllers)) {
        // it's controller slug
        $this->_controller_slug = $this->_collection_slug = $this->_request_args[$i];
        $i++;
      }
    }
    if (isset($this->_request_args[$i])) {
      // it's item slug
      $this->_resource_slug = $this->_request_args[$i];
      // does it have extension?
      $ext_pos = strrpos($this->_resource_slug, '.');
      if ($ext_pos) {
        $this->_resource_extension = substr($this->_resource_slug, $ext_pos + 1);
        $this->_resource_slug = substr($this->_resource_slug, 0, $ext_pos);
      }
      $i++;
    }
    $this->_resource_args = array_slice($this->_request_args, $i);
    foreach($this->_resource_args as $arg){
      $arg_parts = \explode($this->_uri_assoc_separator, $arg);
      if(isset($arg_parts[0])){
        switch(\count($arg_parts)){
          case 1: $this->_resource_assoc_args[$arg_parts[0]] = null; break;
          case 2: $this->_resource_assoc_args[$arg_parts[0]] = $arg_parts[1]; break;
          default: $key = array_shift($arg_parts); $this->_resource_assoc_args[$key] = $arg_parts; break;
        }
      }
    }
  }

  /**
   * load language using lng slug set in language_slug
   */
  private function _load_language() {
    
  }

  function parseBrowser() {
    $this->_parse_browser();
  }

  private function _parse_browser() {
    $this->_browser = \get_browser(null, true);
  }

  public function getUri() {
    return $this->_uri;
  }
  
  public function getRequestType() {
    return $this->_request_type;
  }

  public function getUriArgs() {
    return $this->_uri_args;
  }

  public function getRequest() {
    return $this->_request;
  }

  public function getRequestArgs() {
    return $this->_request_args;
  }

  function getLanguageSlug() {
    return $this->_language_slug;
  }

  function getCollectionSlug() {
    return $this->_collection_slug;
  }
  function setCollectionSlug($slug) {
    return $this->_collection_slug = $slug;
  }

  function getControllerSlug() {
    return $this->_controller_slug;
  }
  function setControllerSlug($slug) {
    return $this->_controller_slug = $slug;
  }

  public function getResourceSlug() {
    return $this->_resource_slug;
  }

  public function getResourceArgs() {
    return $this->_resource_args;
  }

  public function getResourceAssocArgs() {
    return $this->_resource_assoc_args;
  }
  public function getCliScriptName() {
    return $this->_cli_script_name;
  }

  public function getCliArgs() {
    return $this->_cli_args;
  }

    public function getResourceExtension() {
    return $this->_resource_extension;
  }

  public function getFormat() {
    return $this->_format;
  }

  public function getParameters() {
    return $this->_parameters;
  }

  public function getBody() {
    return $this->_body;
  }

  public function getBrowser() {
    return $this->_browser;
  }

  function getLanguage() {
    return $this->_language;
  }

}

/**
 * @since		Version 0.0.3
 */
final class Responder extends Object {

  private $_responder_type = false;
  private $_response = false;

  function respondeFromCache() {
    return false;
    // uri? maybe use controller+item slug???
    $this->_response = yafa_cache()->get(yafa_requester()->getUri());
    if ($this->_response) {
      $this->setResponderType('html');
      $this->responde();
      return true;
    }
    return false;
  }

  final function responde() {
    $this->_resolve_responder_type();
    
    switch ($this->_responder_type){
      case 'html': $this->_html_responde(); break;
      case 'xml': $this->_xml_responde(); break;
      case 'json': $this->_json_responde(); break;
      default : $this->_row_responde();
    }
  }
  
  public function getResponderType() {
    return $this->_responder_type;
  }

  public function setResponderType($responder_type) {
    $this->_responder_type = $responder_type;
  }

    
  private function _resolve_responder_type(){
    if(!$this->_responder_type){
      return;
    }
    $this->_responder_type = yafa_requester()->getFormat();
  }
  
  private function _html_responde(){
    $this->_row_responde();
  }
  private function _xml_responde(){
    $this->_row_responde();
  }
  private function _json_responde(){
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Content-type: application/json');
    echo ( ($this->_response) ? $this->_response : yafa_controller()->getContent() );
    return;
//    $this->_row_responde();
  }
  private function _row_responde(){
    //dbg('row responde');
    print_r( ($this->_response) ? $this->_response : yafa_controller()->getContent() );
  }

}

/**
 * @since		Version 0.0.1
 */
abstract class YafaModel extends Object {
  //put your code here
}

/**
 * @since		Version 0.0.1
 */
abstract class YafaView extends Object {

  //put your code here
  final function view($with, $wath) {
    return $this->$with($wath);
  }
  
  function formatHtml($html){
    $dom = new \DOMDocument('1.0');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadHTML($html);
    return $dom->saveHTML();
  }

}

/**
 * @since		Version 0.0.1
 */
abstract class YafaController extends Object {

  protected $content;
  function controll(){
    if (method_exists($this, yafa_requester()->getResourceSlug())) {
      $this->{yafa_requester()->getResourceSlug()}();
      return;
    }
    $this->addMessage(yafa_requester()->getResourceSlug());

    return \yafa\core\YAFA::CONTROLLER_MISSING_ACTION;
  }
  function atest() {
    dbg('atest');
  }
  public function getContent() {
    return $this->content;
  }

  public function setContent($content) {
    $this->content = $content;
  }

  

//  final function controll($method) {
//    return $this->$method();
//  }
}

?>