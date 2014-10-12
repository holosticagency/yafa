<?php
namespace holisticagency\yafa\core\interfaces;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Panajotis Zamos <aqw1137@gmail.com>
 */
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
