<?php
/**
 * Description of yapi_session_0
 *
 * @author panas
 */
namespace yafa\yapi\test;
class YapiTest extends \yafa\yapi\YapiNull implements \yafa\interfaces\YafaApi {
  function get_responde() {
    $dbg = array();
    $dbg['foo'] = 'this is yapi test';
    $dbg['bar'] = array('asd',123,'fgh');
    $dbg['yapi_name'] = yapi('test')->get_name();
    
    return $dbg;
  }
  function get_info() {
    return array(
      'foo' => 'bar',
      'author' => 'panos',
      'date' => '2013-08-15'
    );
  }
}
?>
