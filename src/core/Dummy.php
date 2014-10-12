<?php
namespace holisticagency\yafa\core;

use holisticagency\yafa;

/**
 * @author Panajotis Zamos <aqw1137@gmail.com>
 * @since		Version 0.0.2
 */
class Dummy extends Object {

  // used if calling class not loaded
  public function __call($method_name, $arguments) {
    //dbgx('FAIL!!!');
  }

}
