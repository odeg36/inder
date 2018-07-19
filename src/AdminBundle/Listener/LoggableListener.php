<?php

namespace AdminBundle\Listener;

use Gedmo\Loggable\LoggableListener as baseLoggableListener;

/**
* Loggable listener
*
* @author Boussekeyt Jules <jules.boussekeyt@gmail.com>
* @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
* @license MIT License (http://www.opensource.org/licenses/mit-license.php)
*/
class LoggableListener extends baseLoggableListener
{
    function __construct(){
        
    }
    /**
    * Set username for identification
    *
    * @param mixed $username
    *
    * @throws \Gedmo\Exception\InvalidArgumentException Invalid username
    */
   public function setUsername($username)
   {
       if (is_string($username)) {
           $this->username = $username;
       } elseif (is_object($username) && method_exists($username, 'getUsername')) {
           $this->username = (string) $username->getUsername();
       } else {
           $this->username = 'anon';
       }
   }
}