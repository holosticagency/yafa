<?php
namespace holisticagency\yafa\core;

use holisticagency\yafa;

/**
 * @author Panajotis Zamos <aqw1137@gmail.com>
 * @since		Version 0.0.1
 */
abstract class View extends Object {

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
