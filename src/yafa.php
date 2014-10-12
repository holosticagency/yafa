<?php
/**
 *  YAFA [yet another framework acronym]
 *
 * <p>
 * PNP pnp PhpNginxPostgres (redis?)
 * <ul>
 *   <li>yafa is fast, readable, simple, scalable & secure</li>
 *   <li>yafa RESTful http://en.wikipedia.org/wiki/REST</li>
 * </ul>
 * feb. 2011.
 * </p>
 *
 * @package yafa_core
 * @version 0.4.0
 * @author panajotis zamos [aqw137@gmail.com]
 * @copyright	Copyright (c) 2011-2014, PLZ, Inc.
 * @license http://opensource.org/licenses/MIT MIT license
 * @since		0.0.1
 *
 * <p>
 * Version timeline:
 * <ul>
 *   <li>0.0.1 - 0.0.5 : feb. 2011 - feb. 2012.</li>
 *   <li>0.0.6 : feb. 2012. - </li>
 *   <li>0.1.0 : mart. 2012. - </li>
 *   <li>0.2.0 : jan. 2013. - </li>
 *   <li>0.3.0 : avg. 2014. - </li>
 *   <li>0.4.0 : oct. 2014. - </li>
 * </ul>
NOTE: last version should be the same as defined in YAFA_VERSION below
 * </p>
 */
namespace holisticagency\yafa;

function _YAFA_(){}

//set_error_handler("yafa_error_handler");
//set_error_handler([new asd(),"yafa_error_handler"]);

define('YAFA_VERSION', '0.4.0');
/**
 * no debug
 */
define('YAFA_DEBUG_OFF', 0);
/**
 * debug in development
 */
define('YAFA_DEBUG_DEV', 1);
define('YAFA_DEBUG_PROD', 2);
define('YAFA_DEBUG_XPROFILE', 4);

$holder = new YafaHolder();
$holder->hold('debug', new YafaDebug());

//yafa_debug()->setLevel(YAFA_DEBUG_XPROFILE);
//yafa_debug()->setLevel(YAFA_DEBUG_DEV);

timer('app');
yafa_debug()->start_xprofile();

define('DS', DIRECTORY_SEPARATOR);

// all application init (define custom constants (HOST, YAFA_APP_DIR ...))

if (!defined('HOST')) {
  define('HOST', isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'example.com');
}
if (!defined('DOMAIN')) {
  define('DOMAIN', 'http://' . HOST); //TODO don't hardcode protocol (http)
}
if (!defined('YAFA_APP_DIR')) {
  $appdir = 'app' . DS . HOST; //TODO don't use HOST for APP path
  if (!file_exists($appdir)) {
    $appdir = 'app' . DS . 'default';
  }
  define('YAFA_APP_DIR', $appdir);
}
if (!defined('YAFA_SYS_DIR')) { define('YAFA_SYS_DIR', 'yafa');}
if (!defined('YAFA_VENDOR_DIR')) { define('YAFA_VENDOR_DIR', 'vendor');}
if (!defined('YAFA_API_DIR')) { define('YAFA_API_DIR', 'yapi');}
if (!defined('ROOT')) { define('ROOT', dirname(dirname(__FILE__)));}
if (!defined('YAFA_APP_PATH')) { define('YAFA_APP_PATH', ROOT . DS . YAFA_APP_DIR . DS);}
if (!defined('YAFA_SYS_PATH')) { define('YAFA_SYS_PATH', ROOT . DS . YAFA_SYS_DIR . DS);}
if (!defined('YAFA_VENDOR_PATH')) { define('YAFA_VENDOR_PATH', ROOT . DS . YAFA_VENDOR_DIR . DS);}
define('YAFA_API_PATH', YAFA_SYS_PATH . YAFA_API_DIR . DS);

//require_once(YAFA_SYS_PATH . 'YafaInterfaces.php'); // load core yafa interfaces
//require_once(YAFA_SYS_PATH . 'YafaCoreClasses.php'); // load core yafa classes
//require_once(YAFA_API_PATH . 'YafaApiClasses.php'); // load yafa api classes
//require_once(YAFA_SYS_PATH . 'YafaDefaultClasses.php'); // load default yafa classes
//require_once(YAFA_SYS_PATH . 'YafaLoader.php'); // load core yafa loader

$holder->hold('null', new core\Dummy);
$holder->hold('loader', new core\Loader());

//yafa_loader()->loadDefaultYafaClasses();
//yafa_loader()->loadYapiClasses();

if (file_exists(YAFA_APP_PATH . 'local.init.php')) {
  include_once(YAFA_APP_PATH . 'local.init.php');
}
if (file_exists(YAFA_APP_PATH . 'init.php')) {
  include_once(YAFA_APP_PATH . 'init.php');
}

//dbg(yafa_debug()->defined());


$holder->hold('requester', new core\Requester()); // start requester
$holder->hold('responder', new core\Responder()); // and start responder

//$holder->hold('application', new A);

//yafa_application()->run(); // finaly, run application

//yafa_debug()->end_xprofile();
//timer('app',0,1);
/**
 * returns yafa class holder
 * @package yafa_holders
 * @since		0.0.1
 * @return YafaHolder
 */
function yafa_holder() {
  $holder = new YafaHolder();
  return $holder;
}

/**
 * returns yafa Loader class
 * @package yafa_holders
 * @since		0.0.1
 * @return holisticagency\yafa\core\Loader
 */
function yafa_loader() {
  $holder = new YafaHolder();
  return $holder->get('loader');
}

/**
 * returns yafa Debug class
 * @package yafa_holders
 * @since		0.0.1
 * @return YafaDebug
 */
function yafa_debug() {
  //global $holder;
  $holder = new YafaHolder();

  return $holder->get('debug');
}

/**
 * returns yafa Application class
 * @package yafa_holders
 * @since		0.0.5
 * @return holisticagency\yafa\core\Application
 */
function yafa_application() {
  $holder = new YafaHolder();
  return $holder->get('application');
}

/**
 * returns yafa Db class
 * @package yafa_holders
 * @deprecated since version 0.4.0
 * @since		0.0.1
 * @return holisticagency\yafa\core\Db
 */
function yafa_db() {
  $holder = new YafaHolder();
  return $holder->get('db');
}

/**
 * returns yafa Data class
 * @package yafa_holders
 * @deprecated since version 0.4.0
 * @since		0.0.6
 * @return yafa\core\Data
 */
function yafa_data() {
  $holder = new YafaHolder();
  return $holder->get('data');
}

/**
 * return yafa router class
 * @package yafa_holders
 * @since		0.0.1
 * @return holisticagency\yafa\core\Router
 */
function yafa_router() {
  $holder = new YafaHolder();
  return $holder->get('router');
}

/**
 * return yafa requester class
 * @package yafa_holders
 * @since		0.0.4
 * @return holisticagency\yafa\core\Requester
 */
function yafa_requester() {
  $holder = new YafaHolder();
  return $holder->get('requester');
}

/**
 * return yafa responder class
 * @package yafa_holders
 * @since		0.0.4
 * @return holisticagency\yafa\core\Responder
 */
function yafa_responder() {
  $holder = new YafaHolder();
  return $holder->get('responder');
}

/**
 * return yafa common class
 * @package yafa_holders
 * @deprecated since version 0.4.0
 * @since		0.0.1
 * @return \yafa\core\Common
 */
function yafa_common() {
  $holder = new YafaHolder();
  return $holder->get('common');
}

/**
 * return yafa user class
 * @package yafa_holders
 * @deprecated since version 0.4.0
 * @since		0.0.3
 * @return \yafa\core\User
 */
function yafa_user() {
  $holder = new YafaHolder();
  return $holder->get('user');
}

/**
 * return yafa config class
 * @package yafa_holders
 * @since		0.0.3
 * @return holisticagency\yafa\core\Config
 */
function yafa_config() {
  $holder = new YafaHolder();
  return $holder->get('config');
}

/**
 * return yafa cache class
 * @package yafa_holders
 * @since		0.0.3
 * @return holisticagency\yafa\core\Cache
 */
function yafa_cache() {
  $holder = new YafaHolder();
  return $holder->get('cache');
}

/**
 * return yafa controller class
 * @package yafa_holders
 * @since		0.0.3
 * @return holisticagency\yafa\core\interfaces\YafaController
 */
function yafa_controller() {
  $holder = new YafaHolder();
  return $holder->get('controller');
}

/**
 * return yafa language class
 * @package yafa_holders
 * @since		0.0.1
 * @return holisticagency\yafa\core\Language
 */
function yafa_language() {
  $holder = new YafaHolder();
  return $holder->get('language');
}

/**
 * return yafa session class
 * @package yafa_holders
 * @since		0.0.6
 * @return holisticagency\yafa\core\Session
 */
function yafa_session() {
  $holder = new YafaHolder();
  return $holder->get('session');
}

/**
 * YAFA version number
 * @package yafa_core
 * @since		0.0.5
 * @return string in format x.y.z
 */
function yafa_version() {
  return YAFA_VERSION;
}

/**
 * Timer for debug purpose
 *
 * @param type $label
 * @param type $with_memory
 * @param type $print
 * @package yafa_core
 * @since		0.0.1
 * @return type
 */
function timer($label, $with_memory = true, $print = true) {
  return yafa_debug()->timer($label, $with_memory, $print);
}

/**
 * Translate $str to $language
 *
 * @link http://www.php.net/manual/en/function.vsprintf.php
 * @param type $str
 * @param type $strings
 * @param type $language
 * @return type
 */
function __($str, $strings = array(), $language = null){
  if(!is_array($strings) && is_string($strings)){
    $strings = array($strings);
  }
  return vsprintf($str, $strings);
}

/**
 * Return or print data for debug purpose
 *
 * @package yafa_core
 * @since		0.0.1
 * @return type
 */
function dbg() {
  $a = func_get_args();
  if (func_num_args() === 1) {
    return yafa_debug()->debug($a[0], false, array('level' => 1));
  }
  return yafa_debug()->debug($a, false, array('level' => 1));
}

/**
 * Return or print data for debug purpose and exit
 *
 * @package yafa_core
 * @since 0.0.1
 * @return type
 */
function dbgx() {
  $a = func_get_args();
  if (func_num_args() === 1) {
    return yafa_debug()->debug($a[0], true, array('level' => 1));
  }
  return yafa_debug()->debug($a, true, array('level' => 1));
}

/**
 * Class that holds some core yafa classes
 *
 * @package yafa_core
 * @since Version 0.0.3
 */
final class YafaHolder {

  static private $_holding = array();

  /**
   *
   * @param type $name
   * @param type $class
   * @deprecated since version 0.4.0
   * @return type
   */
  function yapi_hold($name,  holisticagency\yafa\core\interfaces\YafaApi $class) {
    return $this->hold($name, $class);
  }
  function hold($name, $class) {
    if (isset(self::$_holding[$name])) {
      \trigger_error("Already holding $name.", \E_USER_WARNING);
      return false;
    }
    self::$_holding[$name] = $class;

    return true;
  }

  function replace($name, $class) {
    self::$_holding[$name] = $class;
    return true;
  }

  /**
   *
   * @param type $name
   * @return type
   */
  function get($name) {
    if (!isset(self::$_holding[$name])) {
      $msg = "Not holding $name.";
      $line = debug_backtrace();
      $k = 1;
      if (isset($line[$k])) {
        if (isset($line[$k]['file'])) {
          $msg .= " [from file {$line[$k]['file']}";
          if (isset($line[$k]['line'])) {
            $msg .= " # {$line[$k]['line']}";
          }
          $msg .= "]";
        }
      }
      //dbg($line);
      //\trigger_error($msg, \E_USER_ERROR);
      \trigger_error($msg, \E_USER_WARNING);

      return self::$_holding['null'];
    }
    return self::$_holding[$name];
//    switch ($name) {
//      case 'loader': return $this->_loader; break;
//      case 'router': return $this->_router; break;
//      default: return false; break;
//    }
  }
  function get_all() {
    return self::$_holding;
  }

}

/**
 * Class that holds some core debuging methods
 *
 * @package yafa_core
 * @since Version 0.0.4
 * @todo replace strpos($_SERVER['REMOTE_ADDR'], '127.0.')
 */
final class YafaDebug {

  function __construct() {
    if(!isset($_SERVER['REMOTE_ADDR']) || strpos($_SERVER['REMOTE_ADDR'], '127.0.') !== false){
      // accessing from local server => in development
      $this->setLevel($this->getLevel() | YAFA_DEBUG_DEV | YAFA_DEBUG_XPROFILE);
    }
  }
  private $_level = 1;

  /**
   *
   * @return type
   */
  public function getLevel() {
    return $this->_level;
  }

  /**
   *
   * @param type $level
   */
  public function setLevel($level) {
    $this->_level = $level;
  }

  /**
   *
   */
  function start_xprofile() {
    if (($this->_level & YAFA_DEBUG_XPROFILE) && isset($_GET['debug']) && function_exists('xhprof_enable')) {
      xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);

      define('YAFA_START_TIME', microtime(true));
      define('YAFA_START_MEMORY_USAGE', memory_get_usage());
    }
  }

  /**
   *
   */
  function end_xprofile() {
    if (!($this->_level & YAFA_DEBUG_XPROFILE) || !isset($_GET['debug']) || !function_exists('xhprof_disable')) {
      return;
      //die();
    }

    $xhprof_data = xhprof_disable();

    $time_diff = microtime(true) - YAFA_START_TIME;
    //echo " -- \$time_diff = $time_diff -- <br/>";
    echo "Page rendered in <b>"
    . round($time_diff, 5) * 1000 . " ms</b>, taking <b>"
    . round((memory_get_usage() - YAFA_START_MEMORY_USAGE) / 1024, 2) . " KB</b>";
    $f = get_included_files();
    echo ", include files: " . count($f);

    include_once YAFA_VENDOR_PATH . "xhprof/xhprof_lib/utils/xhprof_lib.php";
    include_once YAFA_VENDOR_PATH . "xhprof/xhprof_lib/utils/xhprof_runs.php";

    // save raw data for this profiler run using default
    // implementation of iXHProfRuns.
    $xhprof_runs = new \XHProfRuns_Default();

    // save the run under a namespace "xhprof_foo"
    $run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_foo");

    echo ", xhprof <a href=\"http://" . HOST .'/'. YAFA_VENDOR_DIR . "/xhprof/xhprof_html/index.php?run=$run_id&source=xhprof_foo\">url</a>";
    echo ", xhprof <a href=\"http://" . "localhost/xhgiu/web/webroot/run.php?id=$run_id&source=xhprof_foo\">url</a>";


    /*
     * END BENCH CODE
     */
  }

  /**
   *
   * @staticvar array $labels
   * @param type $label
   * @param type $with_memory
   * @param type $print
   * @return type
   * @todo add formating like in debug (for <pre> and <b>)
   */
  function timer($label, $with_memory = true, $print = true) {
    if ($this->_level & (YAFA_DEBUG_PROD | YAFA_DEBUG_DEV)) {
      static $labels = array();
      if (!isset($labels[$label]['TIME_START'])) {
        $labels[$label]['TIME_START'] = \microtime(true);
        $labels[$label]['MEMORY_START'] = \memory_get_usage();
        return;
      }
      $TIME_END = \microtime(true);
      $diff_time = $TIME_END - $labels[$label]['TIME_START'];
      $labels[$label]['TIME_START'] = $TIME_END;
      $time = array(
          'diff' => $diff_time,
          's' => floor($diff_time),
          'ms' => floor($diff_time * 1000) - floor($diff_time) * 1000,
          'us' => floor($diff_time * 1000000) - floor($diff_time * 1000) * 1000,
      );
      if ($print) {
        echo "<pre>";
        //print_r($time);
        echo "----- $label ------------------------";
        echo "\n {$time['s']}s {$time['ms']}ms {$time['us']}us ";
      }

      if ($with_memory) {
        $MEMORY_END = \memory_get_usage();
        $diff_mem = $MEMORY_END - $labels[$label]['MEMORY_START'];
        $labels[$label]['MEMORY_START'] = $MEMORY_END;
        $memory = (!function_exists('memory_get_usage')) ? '0' : round($diff_mem / 1024 / 1024, 2) . 'MB';
        $time['memory'] = $memory;
        if ($print) {
          echo "\n $memory \n";
        }
      }
      if ($print) {
        echo "</pre>";
      }
      return $time;
    }
  }

  /**
   *
   * @param type $data
   * @param type $exit
   * @param type $options
   * @return string
   */
  function debug($data, $exit = false, $options = array()) {
//    var_dump($this->_level);
//    print_r($data);
//    print_r($options);
//    die();
    if ($this->_level & (YAFA_DEBUG_PROD | YAFA_DEBUG_DEV)) {
      if (PHP_SAPI === 'cli'){
        $__options = array(
            'format' => true,
            'return' => false,
            'nested' => false,
            'level' => 0,
            'nl' => "\n",
            'tab' => "\t",
            'b' => false,
            'pre' => false,
        );

      }else{
        $__options = array(
            'format' => true,
            'return' => false,
            'nested' => false,
            'level' => 0,
            'nl' => "\n<br/>",
            'tab' => "\t",
            'b' => true,
            'pre' => true,
        );
      }
      $_options = array();
      foreach ($__options as $k => $v) {
        if (isset($options[$k])) {
          $_options[$k] = $options[$k];
        } else {
          $_options[$k] = $v;
        }
      }
      $options = $_options;
      $_nl = $options['nl'];
      $_tab = $options['tab'];
      if($options['pre']){
        $pre = '<pre>';
        $_pre = '</pre>';
      }else{
        $pre = '';
        $_pre = '';
      }
      if($options['b']){
        $b = '<b>';
        $_b = '</b>';
      }else{
        $b = '';
        $_b = '';
      }

      if (isset($options['format']) && $options['format']) {
        $nl = $_nl;
        $tab = $_tab;
      } else {
        $nl = '';
        $tab = '  ';
      }

      $content = '';
      $content .= $nl;
      $line = debug_backtrace();

      $_level = $options['level'];
      if (isset($line[$_level]['file']) && isset($line[$_level]['line'])) {
        $content .= "$nl --- (#$_level) from file $b $tab";
        $content .= $line[$_level]['file'] . $tab . $tab;
        $content .= "$_b on line: " . $tab;
        $content .= $line[$_level]['line'] . $tab;
        $content .= " with: \t";
        if (isset($line[$_level]['class'])) {
          $content .= $line[$_level]['class'];
        }
        if (isset($line[$_level]['type'])) {
          $content .= $line[$_level]['type'];
        }
        if (isset($line[$_level]['function'])) {
          $content .= $line[$_level]['function'] . $tab;
        }
      }else {
        $content .= "$nl $nl $tab no 'file' or 'line' index in \$line[$_level] = ";
      }

      $content .= $nl;
      if (is_null($data)) {
        $content .= "NULL\n";
      }else{
        if (is_array($data) || \is_object($data)) {
          $content .= "$pre\n";
          $content .= print_r($data, true);
          $content .= "\n$_pre";
        } elseif (is_bool($data)) {
          if ($data === false) {
            $content .= 'FALSE';
          } else {
            $content .= 'TRUE';
          }
        } else {
          $content .= $nl . $data;
        }
      }
      $content .= $nl;
      if ($options['return']) {
        return $content;
      }

      echo $content;
      if ($exit) {
        exit;
      }
    }
  }

  /**
   * log custom message to custom file
   *
   * @param string $msg log message
   * @param string $file path and filename where to save log message
   * @param mixed $time time string or TRUE for current time (in format of 'Y-m-d H i s')
   */
  function log($msg, $file = '/tmp/YAFA_APP_LOG.log', $time = true){
    $log = '';
    if($time === true){
      $log .= '@';
      $log .= date('Y-m-d H i s');
      $log .= ' : ';
    }elseif(is_string($time)){
      $log .= $time;
    }

    $log .= $msg;

    \error_log($log, 3, $file);
  }

  function defined(){
    $defined = array(
        'DOMAIN' => DOMAIN, 'HOST' => HOST, 'ROOT' => ROOT,
        'YAFA_APP_DIR' => YAFA_APP_DIR, 'YAFA_APP_PATH' => YAFA_APP_PATH,
        'YAFA_VENDOR_DIR' => YAFA_VENDOR_DIR, 'YAFA_VENDOR_PATH' => YAFA_VENDOR_PATH,
        'YAFA_SYS_DIR' => YAFA_SYS_DIR, 'YAFA_SYS_PATH' => YAFA_SYS_PATH
    );

    return $defined;
  }
  function yapi_in_use(){
    $return = array();
    if(function_exists('yapi_get_all')){
      $yapis = yapi_get_all();
      foreach($yapis as $yapi_name => $yapi){
        $info = array();
        $info['name'] = $yapi->get_name();
        $info['version'] = $yapi->get_version();
        $info['info'] = $yapi->get_info();
        $return[$yapi_name] = $info;
      }
    }
    return $return;
  }
  function info(){
    $dbg = array(
        'version' => YAFA_VERSION,
        'defined_consts' => $this->defined(),
        'yapi_in_use' => $this->yapi_in_use(),
        '_SERVER' => $_SERVER,
        'requester' => yafa_requester()->info(),
        'browser' => yafa_requester()->getBrowser(),
    );

    return $dbg;
  }

}

class asd{
function yafa_error_handler($errno, $errstr, $errfile, $errline){
  if (!(error_reporting() & $errno)) {
    // This error code is not included in error_reporting
    return false;
  }

  $dbg = "";
  switch ($errno) {
    case \E_USER_WARNING:
      $dbg .= "<b>User WARNING</b>";
      $dbg .= " in $errfile at line $errline : \n<pre>\n$errstr \n</pre>\n";
      echo($dbg);
      break;
    case \E_WARNING:
      $dbg .= "<b>System WARNING</b>";
      $dbg .= " in $errfile at line $errline : \n<pre>\n$errstr \n</pre>\n";
      echo($dbg);
      break;
    case \E_ERROR:
    case \E_CORE_ERROR:
    case \E_COMPILE_ERROR:
      $dbg .= "<b>System ERROR ($errno)</b>";
      $dbg .= " in $errfile at line $errline : \n<pre>\n$errstr \n</pre>\n";
      $dbg .= "Aborting...<br />\n";
      echo($dbg);
      exit(1);
      break;
    case \E_USER_ERROR:
      $dbg .= "<b>User ERROR</b>";
      $dbg .= " in $errfile at line $errline : \n<pre>\n$errstr \n</pre>\n";
      $dbg .= "Aborting...<br />\n";
      echo($dbg);
      exit(1);
      break;
    case \E_RECOVERABLE_ERROR:
      $dbg .= "<b>Fatal ERROR</b>";
      $dbg .= " in $errfile at line $errline : \n<pre>\n$errstr \n</pre>\n";
      $dbg .= "Aborting...<br />\n";
      echo($dbg);
      exit(1);
      break;

    default:
      // execute PHP internal error handler
      return false;
  }

  return true;
}
}