<?php
namespace yafa\yapi;

/**
 * yafa api Classes is collection of classes essential for yafa api
 *
 * @package yafa_api
 * @version 0.0.4
 * @author panajotis zamos [aqw137@gmail.com]
 * @copyright	Copyright (c) 2011-2013, PLZ, Inc.
 * @since Version 0.2.0
 * <ul>
 *   <li>$Revision$</li>
 *   <li>$Date$</li>
 *   <li>$Id$</li>
 *   <li>$Author$</li>
 * </ul>
 */

class YapiFactory {
  static protected $name = 'null';
  static protected $head_version = '0.0.0';
  static protected $version = null;
  static private $_config = null;
  public static function get($version = 'head') {
    if(!isset(self::$_config)){
      $oClass = new \ReflectionClass ('YAPI_CONFIG');
      self::$_config = $oClass->getConstants ();
    }
//    dbg(self::$name);
//    dbg($version);
    // using YAPI_CONFIG class
    $YAPI_CONFIG__HEAD_VERSION = strtoupper(static::$name) . "_HEAD_VERSION";
    if(isset(self::$_config[$YAPI_CONFIG__HEAD_VERSION])){
      static::$head_version = self::$_config[$YAPI_CONFIG__HEAD_VERSION];
    }
    // using defined constants
//    $YAPI__HEAD_VERSION = "YAPI_" . strtoupper(static::$name) . "_HEAD_VERSION";
//    if(defined($YAPI__HEAD_VERSION)){
//      static::$head_version = constant($YAPI__HEAD_VERSION);
//    }
    if($version == '' || $version == 'head'){
      // using YAPI_CONFIG class
      $YAPI_CONFIG__IN_USE_VERSION = strtoupper(static::$name) . "_IN_USE_VERSION";
      if(isset(self::$_config[$YAPI_CONFIG__IN_USE_VERSION])){
        $version = self::$_config[$YAPI_CONFIG__IN_USE_VERSION];
      }else{
        $version = static::$head_version;
      }
    // using defined constants
//      $YAPI__IN_USE_VERSION = "YAPI_" . strtoupper(static::$name) . "_IN_USE_VERSION";
//      if(defined($YAPI__IN_USE_VERSION)){
//        $version = constant($YAPI__IN_USE_VERSION);
//      }else{
//        $version = static::$head_version;
//      }
    }
//    dbg($version);
    if($version == 'head'){
      $version = static::$head_version;
    }
    static::$version = $version;
    
    $filename = YAFA_API_PATH . static::$name . DS . 'yapi_' . static::$name . '_' . static::$version . '.php';
//    dbg($filename);
    if(file_exists($filename)){
      include_once $filename;
      
      $yapi_class = 'Yapi' . ucfirst(static::$name);
      $yapi_class = '\\yafa\\yapi\\'.static::$name.'\\' . $yapi_class;
      $yapi_object = new $yapi_class(static::$name, static::$version);
      //dbg($yapi_class);
      if(is_subclass_of($yapi_object, '\yafa\yapi\YapiNull')){
        //dbg("OK for $yapi_class ($filename)");
        return $yapi_object;
      }else{
        //dbg("NOK for $yapi_class ($filename)");
      }
    }
    //dbg($filename . '___fail, returning YapiNull :(');
    return new \yafa\yapi\YapiNull('Null', '0.0.0');
  }
}
class YapiNull implements \yafa\interfaces\YafaApi{
  protected $_name = 'Null';
  protected $_version = '0.0.0';
  public function __construct($name, $version) {
    $this->_name = $name;
    $this->_version = $version;
  }

  /**
   * @return string returns YafaAPI name
   */
  public function get_name(){return $this->_name;}
  /**
   * returns version number following the rules of Semantic Versioning at semver.org
   * @return string returns YafaAPI version number
   */
  public function get_version(){return $this->_version;}
  /**
   * returns varius information about this api
   * @return array returns YafaAPI varius information
   */
  public function get_info(){return array();}
  /**
   * @param array $request request as array
   * @return array returns
   */
  public function set_request(array $request){}
  /**
   * 
   * @return array returns YafaAPI varius information
   */
  public function get_responde(){return array();}

}
?>
