<?php
namespace holisticagency\yafa\core;

use holisticagency\yafa;

/**
 * Loader should load yafa classes
 * and enable autoload for APP and VENDOR classes
 *
 * @package yafa_core
 * @version 0.0.5
 * @author panajotis zamos [aqw137@gmail.com]
 * @copyright	Copyright (c) 2012, PLZ, Inc.
 * @since 0.0.2
 *
 *  jan. 2012
 *  $Revision$
 *  $Date$
 *  $Id$
 *  $Author$
 */
final class Loader {
  private $_namespaceSeparator = '\\';
  private $_fileExtension = '.php';

  function atest(){
    dbg('atest in loader');
  }

  /**
   * load lib by path (from Vendor dir)
   *
   * @todo security check
   * @param string $path
   */
  function loadByPath($path){
    require_once YAFA_VENDOR_PATH . $path;
  }

  /**
   * load classes that are not in yafa/Classes.php
   */
  function loadDefaultYafaClasses(){
    //dbg('in loader loadDefaultYafaClasses()');
    // nothing to load for now :)
    //spl_autoload_register(array(yafa\yafa_loader(), '_yafa_class_loader'));
  }
  /**
   *
   */
  function loadYafaClasses(){
    spl_autoload_register(array(yafa\yafa_loader(), '_yafa_class_loader'));
  }
  /**
   * load classes that are in app dir
   */
  function loadAppClasses(){
    spl_autoload_register(array(yafa\yafa_loader(), '_app_class_loader'));
  }
  /**
   * load classes that are in vendor dir
   */
  function loadVendorClasses(){
    spl_autoload_register(array(yafa\yafa_loader(), '_vendor_class_loader'));
  }
  /**
   * load classes that are in vendor dir
   */
  function loadYapiClasses(){
    spl_autoload_register(array(yafa\yafa_loader(), '_yapi_class_loader'));
  }

  /**
   * load classes that are in vendor dir
   */
  function missingClassHandler(){
    spl_autoload_register(array(yafa\yafa_loader(), '_missing_class_handler'));
  }

  private function _missing_class_handler($className){
    $error_msg = "Class $className fail to load.";
    trigger_error($error_msg, \E_USER_ERROR);
  }

  /**
   * YAFA classes should have namespace that corresponds to thair path and Class name should be the name of the php file.
   * use example:
   * for class Test with NS /mvc/model used on HOST example.com defined in /var/www/yafa/yafa/mvc/model/Test.php
   * @param type $className
   */
  private function _yafa_class_loader($className){
    $this->_loader($className, ROOT . DS);
    //$this->_loader($className, YAFA_SYS_PATH);
  }
  /**
   * APP classes should have namespace that corresponds to thair path and Class name should be the name of the php file.
   * use example:
   * for class Test with NS /mvc/model used on HOST example.com defined in /var/www/yafa/app/example.com/mvc/model/Test.php
   * @param type $className
   */
  private function _app_class_loader($className){
    $this->_loader($className, YAFA_APP_PATH);
  }
  /**
   * VENDOR classes should have namespace that corresponds to thair path and Class name should be the name of the php file.
   * use example:
   * for class Test with NS /Foo/bar used on HOST example.com defined in /var/www/yafa/vendor/Foo/bar/Test.php
   * @param type $className
   */
  private function _vendor_class_loader($className){
    $this->_loader($className, YAFA_VENDOR_PATH);
  }
  /**
   * Yapi factory classes should have namespace that corresponds to thair path and Class name should be the name of the php file.
   * use example:
   * for class TestFactory with NS /Foo/bar used on HOST example.com defined in /var/www/yafa/yapi/Foo/bar/TestFactory.php
   * @param type $className
   */
  private function _yapi_class_loader($className){
//    dbg($className);
//    dbg(ROOT . DS);
//    dbg(YAFA_API_PATH);
    //dbgx(YAFA_API_PATH);
    $this->_loader($className, ROOT . DS);
  }
  private function _loader($className, $in){
    $_namespaceSeparator = $this->_namespaceSeparator;
    $_fileExtension = $this->_fileExtension;

    $fileName = '';
    $namespace = '';
    //dbg(\compact('namespace', 'className', 'fileName'));
    if (false !== ($lastNsPos = strripos($className, $_namespaceSeparator))) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace($_namespaceSeparator, \DS, $namespace) . \DS;
    }

    //dbg(\compact('namespace', 'className', 'fileName', 'in'));
    $full_filename = $in . $fileName . $className . $_fileExtension;
    if(file_exists($full_filename)){
      include $full_filename;
    }
  }
}
?>