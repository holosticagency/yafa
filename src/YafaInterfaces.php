<?php
namespace yafa\interfaces;
/**
 * @package yafa_core
 * @author panajotis zamos [aqw137@gmail.com]
 * @since Version 0.0.2
 * @copyright	Copyright (c) 2012, PLZ, Inc.
 * <ul>
 *   <li>$Revision$</li>
 *   <li>$Date$</li>
 *   <li>$Id$</li>
 *   <li>$Author$</li>
 * </ul>
 */
interface _yafa_interfaces_{}

/**
 * interface to all controllers to implement
 */
interface YafaController {
//  public function noncontroll();
  public function atest();
  public function controll();
  public function getContent();
}
interface YafaModel {
}
interface YafaResponderEngine {
}
interface YafaApi {
  /**
   * @return string returns YafaAPI name
   */
  public function get_name();
  /**
   * returns version number following the rules of Semantic Versioning at semver.org
   * @return string returns YafaAPI version number
   */
  public function get_version();
  /**
   * returns varius information about this api
   * @return array returns YafaAPI varius information
   */
  public function get_info();
  /**
   * @param array $request request as array
   * @return array returns
   */
  public function set_request(array $request);
  /**
   * 
   * @return array returns YafaAPI varius information
   */
  public function get_responde();
  
}

?>