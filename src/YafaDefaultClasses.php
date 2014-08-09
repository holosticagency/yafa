<?php
namespace yafa\default_classes;
/**
 * Description of YafaDefaultClasses
 *
 * @package yafa_core
 * @author panajotis zamos [aqw137@gmail.com]
 * @copyright	Copyright (c) 2012, PLZ, Inc.
 * <ul>
 *   <li>$Revision: ddf4e6c46897 $</li>
 *   <li>$Date: 2012/02/26 20:51:55 $</li>
 *   <li>$Id: YafaDefaultClasses.php,v ddf4e6c46897 2012/02/26 20:51:55 aqw137 $</li>
 *   <li>$Author: aqw137 $</li>
 * </ul>
 */
class _yafa_default_classes_{}

/**
 * @since Version 0.0.4
 */
class PageController extends \yafa\core\YafaController implements \yafa\interfaces\YafaController{
  public function controll(){
    dbg('controlling in PageController :)');
    
    //$this->_data_test();
  }

  private function _data_test(){
    
    //yafa_db()->hdm_test();
    
    // prepare request
    $type = 'select';
    $query = array(
        '_s' => array('i'=>'id,name', 'ic'=>'item_id,category_id', 'is'=>'item_id,section_id'),
        '_s' => array('i'=>'id,name'),
        '_s' => array('i'=>'id,name', 'ic'=>'item_id,category_id'),
        '_s' => array('i'=>'id,name', 'ic' => '*', 'sc'=>'*'),
        '_f' => 'TestItem i',
        '_lj' => array(
          array('_l' => 'i.TestItemCategories ic'),
          array('_l' => 'ic.TestCategorySubcategories sc'),
            ),
        '_w' => array('i.id =?' => 2)
    );
            
    yafa_data()->request($type, $query);
    $responde = yafa_data()->responde();
    if($responde['status']){
      // error ocured, see $responde['message']
    }else{
      // status OK (it's 0)
    }
    
//    dbg($type);
//    dbg($query);
//    dbg($responde);
    dbg(compact('type', 'query', 'responde'));
  }
  
  function aa(){
    $html = <<<'EOL'
........
EOL;
    dbg(yafa_cache()->set('cc', $html));
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

/**
 * @since Version 0.0.4
 */
class PageView extends \yafa\core\YafaView{
  function hello_world($to_render){
    
    $sql = "select i.id as i__id, i.name as i__name, ic.item_id as ic__item_id, ic.category_id as ic__category_id, cs.category_id as cs__category_id, cs.subcategory_id as cs__subcategory_id   from test_item i left join test_item_category ic on i.id=ic.item_id left join test_category_subcategory cs on ic.category_id=cs.category_id where i.id = 2;";
    $q = array(
        '_s' => array('i'=>'id,name', 'ic'=>'item_id,category_id', 'cs'=>'category_id,subcategory_id'),
        '_f' => 'test_item i',
        '_lj' => array(
          array('_l' => 'test_item_category ic', '_o' => 'i.id=ic.item_id'),
          array('_l' => 'test_category_subcategory sc', '_o' => 'ic.category_id=sc.category_id'),
            ),
        '_w' => array('i.id =2')
    );
    //yafa_db()->query2($q);
    //yafa_db()->test();
    
    // red bean tests
    //yafa_db()->rb_test();
    
    // HDM tests
    yafa_db()->hdm_test();
    
    return "
<html>
<head>
    <title>Hello World</title>
</head>
<body>
    $to_render
</body>
</html>";
  }
}

/**
 * @since Version 0.0.4
 */
//class HtmlResponder extends \yafa\core\Responder{
//  function render($response){
//    echo $response;
//  }
//}
?>