<?php
namespace holisticagency\yafa\defaults;

use holisticagency\yafa;

/**
 * @author Panajotis Zamos <aqw1137@gmail.com>
 * @since Version 0.0.4
 */
class PageController extends \holisticagency\yafa\core\Controller implements \holisticagency\yafa\core\interfaces\YafaController{
  public function controll(){
    \holisticagency\yafa\dbg('controlling in PageController :)');

    //$this->_data_test();
  }

  function aa(){
    $html = <<<'EOL'
........
EOL;
    \holisticagency\yafa\dbg(\holisticagency\yafa\yafa_cache()->set('cc', $html));
  }
  function hello_world(){
    return "<h1>Hello World</h1>";
  }
  function useView(){
    return 'PageView';
  }
  function atest(){
    echo 'ATEST! <br/>';
  }
  function useRender(){
    return 'hello_world';
  }
  function serilizetest(){
    $x = false;
    $x = 'blabla';
    $x = array();
    $r = @\igbinary_unserialize($x);
    dbg($r);
  }
}
