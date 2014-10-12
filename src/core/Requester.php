<?php
namespace holisticagency\yafa\core;

use holisticagency\yafa;

/**
 * @author Panajotis Zamos <aqw1137@gmail.com>
 * @since		Version 0.0.4
 */
final class Requester extends Object {

  private $_uri = '';
  private $_request = '';
  private $_uri_separator = '/';
  private $_uri_assoc_separator = ':';
  private $_cli_separator = '/';
  private $_cli_assoc_separator = ':';
  private $_request_separator = '/';
  private $_request_assoc_separator = ':';
  private $_uri_args = array();
  private $_request_args = array();
  private $_resource_args = array();
  private $_resource_assoc_args = array();
  private $_cli_script_name = '';
  private $_cli_args = array();
  private $_language_slug = '';
  private $_collection_slug = '';
  private $_controller_slug = '';
  private $_resource_slug = '';
  private $_resource_extension = '';
  private $_request_type = '';
  private $_format = '';
  private $_parameters = array();
  private $_body;
  private $_browser = array();
  private $_language = null;

  function __construct() {
//    dbg($_GET);
//    dbgx($_SERVER);
    if (PHP_SAPI === 'cli'){
      $this->_parse_cli_params();
    }else{
      $this->_parse_uri_params();
      $this->_parse_incoming_params();
      $this->_load_language();
    }
  }

  /**
   *
   */
  function info() {
    $info = array(
      'uri' => $this->_uri,
      'uri_separator' => $this->_uri_separator,
      'uri_assoc_separator' => $this->_uri_assoc_separator,
      'cli_separator' => $this->_cli_separator,
      'cli_assoc_separator' => $this->_cli_assoc_separator,
      'request_separator' => $this->_request_separator,
      'request_assoc_separator' => $this->_request_assoc_separator,
      'uri_args' => $this->_uri_args,
      'request' => $this->_request,
      'request_args' => $this->_request_args,
      'cli_script_name' => $this->_cli_script_name,
      'cli_args' => $this->_cli_args,
      'language_slug' => $this->_language_slug,
      'collection_slug' => $this->_collection_slug,
      'controller_slug' => $this->_controller_slug,
      'resource_slug' => $this->_resource_slug,
      'resource_extension' => $this->_resource_extension,
      'resource_args' => $this->_resource_args,
      'resource_assoc_args' => $this->_resource_assoc_args,
      'request_type' => $this->_request_type,
      'format' => $this->_format,
      'parameters' => $this->_parameters,
      'body' => $this->_body,
      '_POST' => $_POST,
    );

    return $info;
  }

  private function _parse_incoming_params() {
    if(!isset($_SERVER['REQUEST_METHOD'])){
      return;
    }
    $parameters = array();

    $this->_request_type = \strtolower($_SERVER['REQUEST_METHOD']);
    // first of all, pull the GET vars
    if (isset($_SERVER['QUERY_STRING'])) {
      parse_str($_SERVER['QUERY_STRING'], $parameters);
    }

    // now how about PUT/POST bodies? These override what we got from GET
    $body = file_get_contents("php://input");
    $this->_body = $body;


    $content_type = false;
    if (isset($_SERVER['CONTENT_TYPE'])) {
      $content_type = $_SERVER['CONTENT_TYPE'];
    }
    switch ($content_type) {
      case "application/json":
        $body_params = json_decode($body);
        if ($body_params) {
          foreach ($body_params as $param_name => $param_value) {
            $parameters[$param_name] = $param_value;
          }
        }
        $this->_format = "json";
        break;
      case "application/x-www-form-urlencoded":
        parse_str($body, $postvars);
        foreach ($postvars as $field => $value) {
          $parameters[$field] = $value;
        }
        $this->_format = "html";
        break;
      default:
        // we could parse other supported formats here
        break;
    }
    $this->_parameters = $parameters;
    if (empty($this->_resource_extension)) {
      $this->_resource_extension = $this->_format;
    }
  }

  private function _parse_cli_params() {
    if(!isset($_SERVER['argv'])){
      return;
    }
    if(count($_SERVER['argv']) > 1){
      $this->_cli_args = $_SERVER['argv'];
      $this->_cli_script_name = array_shift($this->_cli_args);
    }else{
      $this->_cli_script_name = $_SERVER['argv'][0];
    }

    $this->_request_separator = $this->_cli_separator;
    $this->_request_assoc_separator = $this->_cli_assoc_separator;
    if(isset($this->_cli_args[0])){
      $this->_request_args = \explode($this->_cli_separator, $this->_cli_args[0]);
    }
    $this->_parse_request();
  }
  private function _parse_uri_params() {
    if(!isset($_SERVER['REQUEST_URI'])){
      return;
    }
    $this->_uri = \trim($_SERVER['REQUEST_URI'], $this->_uri_separator);
    $this->_uri_args = \explode($this->_uri_separator, $this->_uri);
    $this->_request = isset($_GET['q']) ? $_GET['q'] : $this->_uri;
    $this->_request = \trim($this->_request, $this->_uri_separator);
    $this->_request_args = \explode($this->_uri_separator, $this->_request);
    $this->_parse_request();
  }

  private function _parse_request(){
    //$custom_controllers = yafa\yafa_config()->get('custom_controllers');
    // Not holding config in this moment, get custom controllers manually
    $_custom_controllers = array('test');
    global $custom_controllers;
    if (!isset($custom_controllers)) {
      $custom_controllers = array();
    }
    $custom_controllers = array_merge($_custom_controllers, $custom_controllers);
    //dbg($custom_controllers);

    $i = 0;
    $leng_slug_len = 2;
    $ctrl_slug_len = 3;
    if (isset($this->_request_args[$i])) {
      if (\strlen($this->_request_args[$i]) == $leng_slug_len) {
        // it's language slug
        $this->_language_slug = $this->_request_args[$i];
        $i++;
      }
    }
    if (isset($this->_request_args[$i])) {
      if (\strlen($this->_request_args[$i]) == $ctrl_slug_len || \in_array($this->_request_args[$i], $custom_controllers)) {
        // it's controller slug
        $this->_controller_slug = $this->_collection_slug = $this->_request_args[$i];
        $i++;
      }
    }
    if (isset($this->_request_args[$i])) {
      // it's item slug
      $this->_resource_slug = $this->_request_args[$i];
      // does it have extension?
      $ext_pos = strrpos($this->_resource_slug, '.');
      if ($ext_pos) {
        $this->_resource_extension = substr($this->_resource_slug, $ext_pos + 1);
        $this->_resource_slug = substr($this->_resource_slug, 0, $ext_pos);
      }
      $i++;
    }
    $this->_resource_args = array_slice($this->_request_args, $i);
    foreach($this->_resource_args as $arg){
      $arg_parts = \explode($this->_uri_assoc_separator, $arg);
      if(isset($arg_parts[0])){
        switch(\count($arg_parts)){
          case 1: $this->_resource_assoc_args[$arg_parts[0]] = null; break;
          case 2: $this->_resource_assoc_args[$arg_parts[0]] = $arg_parts[1]; break;
          default: $key = array_shift($arg_parts); $this->_resource_assoc_args[$key] = $arg_parts; break;
        }
      }
    }
  }

  /**
   * load language using lng slug set in language_slug
   */
  private function _load_language() {

  }

  function parseBrowser() {
    $this->_parse_browser();
  }

  private function _parse_browser() {
    $this->_browser = \get_browser(null, true);
  }

  public function getUri() {
    return $this->_uri;
  }

  public function getRequestType() {
    return $this->_request_type;
  }

  public function getUriArgs() {
    return $this->_uri_args;
  }

  public function getRequest() {
    return $this->_request;
  }

  public function getRequestArgs() {
    return $this->_request_args;
  }

  function getLanguageSlug() {
    return $this->_language_slug;
  }

  function getCollectionSlug() {
    return $this->_collection_slug;
  }
  function setCollectionSlug($slug) {
    return $this->_collection_slug = $slug;
  }

  function getControllerSlug() {
    return $this->_controller_slug;
  }
  function setControllerSlug($slug) {
    return $this->_controller_slug = $slug;
  }

  public function getResourceSlug() {
    return $this->_resource_slug;
  }

  public function getResourceArgs() {
    return $this->_resource_args;
  }

  public function getResourceAssocArgs() {
    return $this->_resource_assoc_args;
  }
  public function getCliScriptName() {
    return $this->_cli_script_name;
  }

  public function getCliArgs() {
    return $this->_cli_args;
  }

    public function getResourceExtension() {
    return $this->_resource_extension;
  }

  public function getFormat() {
    return $this->_format;
  }

  public function getParameters() {
    return $this->_parameters;
  }

  public function getBody() {
    return $this->_body;
  }

  public function getBrowser() {
    return $this->_browser;
  }

  function getLanguage() {
    return $this->_language;
  }

}
