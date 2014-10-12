<?php
namespace holisticagency\yafa;

require_once 'yafa.php';

/**
 * Container for application works with {@see run()}
 *
 * @author Panajotis Zamos <aqw1137@gmail.com>
 * @since	 Version 0.0.4
 */
final class Application extends core\Object {

  function run($config = []) {
    //dbg(yafa_debug()->info());
    //yapi()->test()->getContent();

    if (PHP_SAPI === 'cli'){
      $this->_cli_app($config);
    }else{
      $this->_web_app($config);
    }

    //dbg(yafa_debug()->info());
  }

  private function _web_app($config){
    yafa_loader()->loadAppClasses(); // try classes in app dir
    yafa_loader()->loadVendorClasses(); // than try classes in vendor dir
    yafa_loader()->loadYafaClasses(); // and finaly try in yafa dir

//    yafa_holder()->hold('cache', new Cache());
//    // check if requested resource is in cache
//    if (yafa_responder()->respondeFromCache()) {
//      return;
//    }

    // is session needed before cache and responder?
    if(isset($_SERVER['REMOTE_ADDR'])){
      yafa_holder()->hold('session', core\Session::getInstance());
    }

//    yafa_holder()->hold('db', new Db());
//    yafa_holder()->hold('data', new Data());
    yafa_holder()->hold('config', new core\Config());
    yafa_config()->setConfig($config);

//    yafa_holder()->hold('user', new User());
    yafa_loader()->missingClassHandler(); // handl errors for missing classes
//    yafa_holder()->hold('common', new \yafa\Common());

    yafa_holder()->hold('router', new core\Router());
    yafa_router()->engage();

    yafa_responder()->responde();

  }
  private function _cli_app($config){
//    yafa_loader()->loadAppClasses(); // try classes in app dir
//    yafa_loader()->loadVendorClasses(); // than try classes in vendor dir
//    yafa_loader()->loadYafaClasses(); // and finaly try in yafa dir

//    yafa_holder()->hold('cache', new Cache());
//    // check if requested resource is in cache
//    if (yafa_responder()->respondeFromCache()) {
//      return;
//    }
//
//    // is session needed before cache and responder?
//    if(isset($_SERVER['REMOTE_ADDR'])){
//      yafa_holder()->hold('session', Session::getInstance());
//    }

//    yafa_holder()->hold('db', new Db());
//    yafa_holder()->hold('data', new Data());
    yafa_holder()->hold('config', new core\Config());
    yafa_config()->setConfig($config);

//    yafa_holder()->hold('user', new User());
//    yafa_loader()->missingClassHandler(); // handl errors for missing classes // what is this for?
//    yafa_holder()->hold('common', new \yafa\Common());

    yafa_holder()->hold('router', new core\Router());
    yafa_router()->engage();

    yafa_responder()->responde();


  }

}
