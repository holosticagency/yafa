<?php
namespace holisticagency\yafa\core;

use holisticagency\yafa;

/**
 * @author Panajotis Zamos <aqw1137@gmail.com>
 * @since	 Version 0.0.1
 */
class Router extends Object {

  private $_controller = null;

  function engage() {

    $this->_load_controller();
    $status = yafa\yafa_controller()->controll();

    if ($status) {
      $dbg = '';
      switch ($status) {
        case Paja::CONTROLLER_UNKNOWN_ACTION:
          $dbg = yafa\__('Unknown controller action.');
          break;
        case Paja::CONTROLLER_MISSING_ACTION:
//          $this->_missing_controller_action();
//          $sec_status = yafa\yafa_controller()->controll();
//          if ($sec_status) {
            $dbg = yafa\__('Missing controller action: %s', yafa\yafa_controller()->getLastMessage());
//          }
          break;
        default: $dbg = yafa\__('Unknown controller error.');
      }
      if ($dbg) {
        //dbg($dbg);
        trigger_error($dbg, \E_USER_WARNING);
      }
    }
  }

  /**
   * load controller using ctrl slug set in controller_slug
   */
  private function _set_controller(interfaces\YafaController $controller) {
    yafa\yafa_holder()->hold('controller', $controller);
  }

  /**
   * reload controller using ctrl slug set in controller_slug
   */
  private function _reset_controller(interfaces\YafaController $controller) {
    yafa\yafa_holder()->replace('controller', $controller);
  }

  private function _load_controller() {
    $ctrlPath = yafa\yafa_config()->get('default=>controllerPath');
    if (yafa\yafa_requester() ->getControllerSlug() == '') {
      $ctrl_slug = yafa\yafa_config()->get('default=>controller');
    } else {
      $ctrl_slug = yafa\yafa_requester()->getControllerSlug();
    }
    if(isset($ctrl_slug)){
      $ctrl_slug = ucfirst($ctrl_slug);
      yafa\yafa_requester()->setControllerSlug($ctrl_slug);


      $ctrl_name = $ctrlPath . $ctrl_slug;
      $controller = new $ctrl_name();
    }else{
      $controller = new \holisticagency\yafa\defaults\PageController();
    }

    $this->_set_controller($controller);
    return;
  }

  private function _missing_controller_action() {
    $ctrlPath = yafa\yafa_config()->get('default=>controllerPath');
    $ctrl_slug = ucfirst(yafa\yafa_requester()->getControllerSlug());

    $ctrl_name = '\\yafa\\mvc\\controller\\' . $ctrl_slug;
    $controller = new $ctrl_name();
    $this->_reset_controller($controller);
    return;
  }

  function getController() {
    return $this->_controller;
  }

  private function test() {

    echo '<h3>Engaged :)</h3>';

    // yafa\yafa_common()->generateRandStr(22) will trigger warning in this moment cos yafa common is not loaded
    //echo yafa\yafa_common()->generateRandStr(22);
  }

  private function neki_test0() {
    echo '<br>1. pozvano iz routera::test2 ' . yafa\yafa_config()->get('test');
    yafa\yafa_config()->set('test', 'setovano iz router::test');
    // $this->config DEPRECATED  echo '<br>pozvano iz routera::test ' . $this->config->get();
    echo '<br>2. pozvano iz routera::test2 ' . yafa\yafa_config()->get('test');

    $user = new User();
    echo '<br>3. pozvano iz neki test ' . yafa\yafa_config()->set('test', ': setovano iz neki_test()');
    echo '<br>4. pozvano iz usera ' . $user->get_config();
    echo '<br>5. pozvano iz usera ' . $user->set_config(': setovano iz usera');
    echo '<br>6. pozvano iz usera ' . $user->get_config();
    echo '<br>7. pozvano iz neki test ' . yafa\yafa_config()->get('test');
    // yafa\yafa_common()->generateRandStr(22) will trigger warning in this moment cos yafa common is not loaded
    //echo yafa\yafa_common()->generateRandStr(22);
  }

  function neki_test(interfaces\YafaController $ctrl) {


    $test = new \mvc\controller\Test();
    $test->index();
    echo $test->closuretest();

    echo "\n\n<br><br>\n";


    echo '<h2>extra :D </h2>';
    echo '<pre>';
  }

}
