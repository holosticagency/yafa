<?php
/**
 * Description of yapi_session_0.1.0
 *
 * @author panas
 */
namespace yafa\yapi\session;
class YapiSession extends \yafa\yapi\YapiNull implements \yafa\interfaces\YafaApi {
  //public function get_name(){return 'Test';}
  //public function get_version(){return '0.0.1';}
  function get_info() {
    return array(
      'foo' => 'bar',
      'author' => 'panos',
      'date' => '2013-08-15'
    );
  }

}

?>
