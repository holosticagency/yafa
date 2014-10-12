<?php
namespace holisticagency\yafa\defaults;

use holisticagency\yafa;

/**
 * @author Panajotis Zamos <aqw1137@gmail.com>
 * @since Version 0.0.4
 */
class PageView extends \holisticagency\yafa\core\View{
  function hello_world($to_render){
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
