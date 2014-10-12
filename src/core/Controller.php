<?php
namespace holisticagency\yafa\core;

use holisticagency\yafa;

/**
 * @author Panajotis Zamos <aqw1137@gmail.com>
 * @since		Version 0.0.1
 */
abstract class Controller extends Object {

  protected $content;
  function controll(){
    if (method_exists($this, yafa\yafa_requester()->getResourceSlug())) {
      $this->{yafa\yafa_requester()->getResourceSlug()}();
      return;
    }
    $this->addMessage(yafa\yafa_requester()->getResourceSlug());

    return YAFA::CONTROLLER_MISSING_ACTION;
  }
  function atest() {
    yafa\dbg('atest');
  }
  public function getContent() {
    return $this->content;
  }

  public function setContent($content) {
    $this->content = $content;
  }
}
