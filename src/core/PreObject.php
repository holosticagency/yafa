<?php
namespace holisticagency\yafa\core;

/**
 * currently not in use
 *
 * @author Panajotis Zamos <aqw1137@gmail.com>
 * @since		Version 0.0.1
 */
abstract class PreObject {
  private $_messages = array();

  function setMessages($messages) {
    $this->_messages = $messages;
  }

  function addMessage($message) {
    $this->_messages[] = $message;
  }

  function getMessages() {
    return $this->_messages;
  }

  function getLastMessage() {
    return end($this->_messages);
  }

  function getMessagesCount() {
    return count($this->_messages);
  }

  function resetMessages(){
    $this->setMessages(array());
  }


}
