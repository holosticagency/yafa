<?php
namespace holisticagency\yafa\core;

use holisticagency\yafa;

/**
 * @author Panajotis Zamos <aqw1137@gmail.com>
 * @since		Version 0.0.3
 */
final class Responder extends Object {

  private $_responder_type = false;
  private $_response = false;

  function respondeFromCache() {
    // uri? maybe use controller+item slug???
//    $this->_response = yafa\yafa_cache()->get(yafa_requester()->getUri());
//    if ($this->_response) {
//      $this->setResponderType('html');
//      $this->responde();
//      return true;
//    }
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
    $this->_responder_type = yafa\yafa_requester()->getFormat();
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
    echo ( ($this->_response) ? $this->_response : yafa\yafa_controller()->getContent() );
    return;
//    $this->_row_responde();
  }
  private function _row_responde(){
    //dbg('row responde');
    print_r( ($this->_response) ? $this->_response : yafa\yafa_controller()->getContent() );
  }

}
