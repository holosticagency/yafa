<?php
namespace holisticagency\yafa\core;

use holisticagency\yafa;

/**
 * Get and set various config parameters
 *
 * Merge client, tenant, domain, group and user config parameters and
 * provide methods to set and get them.
 *
 * @uses Cache
 * @todo implement set(client|tenant|domain|group|user) variations
 * @author Panajotis Zamos <aqw1137@gmail.com>
 * @since		Version 0.0.1
 */
class Config extends PreObject {

  private $_config = array();

  /*
   * merge client, tenant, domain, group and user config
   */

  public function __construct() {
//    $this->_config = yafa\yafa_cache()->getA(YAFA::CONFIG_CACHE_KEY);
//    if (empty($this->_config)) {
//      // retrive config from db
//    }
    global $config;
    $this->_config = $config;
  }

  private function _save() {
    // save config to db and
    // update cache
  }

  /**
   *
   * @param string $key parameter name
   * @param mixed $value parameter value
   * @return mixed parameter value
   */
  function set($key, $value) {
//    $this->_config[$key] = $value;
//    $this->_save();
//    return $value;
  }

  function setConfig($config) {
    $this->_config = $config;
  }

  function getAll() {
    return $this->_config;
  }
  /**
   *
   * @param string $key parameter name
   * @return mixed parameter value or NULL if not found
   */
  function get($key) {
    $config_delimiter = '=>';
    $key_parts = explode($config_delimiter, $key);
    $_config = $this->_config;
    $_value = null;
    foreach($key_parts as $key_part){
      $key_part = \trim($key_part);
      if (isset($_config[$key_part])) {
        $_config = $_config[$key_part];
      }else{
        return null;
      }
    }
    return $_config;
  }

}
