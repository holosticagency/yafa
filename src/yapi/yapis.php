<?php
/**
 *  YAPI [yafa application programming interface]
 * 
 * <p>
 * yafa API
 * <ul>
 *   <li>yafa API is collection of APIs for use in yafa application as application building blocks</li>
 * </ul>
 * jan. 2013.
 * </p>
 *
 * @package yafa_api
 * @version 0.0.1
 * @author panajotis zamos [aqw137@gmail.com]
 * @copyright	Copyright (c) 2011-2013, PLZ, Inc.
 * @since		0.2.0
 * 
 * <p>
 * repository info:
 * <ul>
 *   <li>$Revision$</li>
 *   <li>$Date$</li>
 *   <li>$Id$</li>
 *   <li>$Author$</li>
 * </ul>
 * </p>
 */


if (file_exists(YAFA_API_PATH . 'local.init.php')) {
  include_once(YAFA_API_PATH . 'local.init.php');
}
if (file_exists(YAFA_API_PATH . 'init.php')) {
  include_once(YAFA_API_PATH . 'init.php');
}

$yapi_holder = new YafaHolder();
$yapi_holder->hold('null', yafa\yapi\YapiFactory::get());
$yapi_holder->hold('test', yafa\yapi\test\TestFactory::get());
$yapi_holder->hold('session', yafa\yapi\session\SessionFactory::get());

/**
 * return YafaAPI
 * @return yafa\interfaces\YafaApi
 */
function yapi($name){
  global $yapi_holder;
  return $yapi_holder->get($name);
}
function yapi_get_all(){
  global $yapi_holder;
  return $yapi_holder->get_all();
}

/**
 * @return YafaAPI
 */
function yapi_test(){
  
}

/**
 * @return YafaAPI
 */
function yapi_cache(){
  
}

/**
 * @return YafaAPI
 */
function yapi_auth(){
  
}

/**
 * alias for yapi_translate()
 * @return YafaAPI
 */
function ___(){
  
}
/**
 * @return YafaAPI
 */
function yapi_translate(){
  
}

/**
 * @return YafaAPI
 */
function yapi_htmlrender(){
  
}

/**
 * @return YafaAPI
 */
function yapi_config(){
  
}

/**
 * @return YafaAPI
 */
function yapi_language(){
  
}

/**
 * @return YafaAPI
 */
function yapi_user(){
  
}

/**
 * @return YafaAPI
 */
function yapi_session(){
  
}

?>
