<?php
/**
 * Created by PhpStorm.
 * User: jorts
 * Date: 4/17/14
 * Time: 1:08 PM
 */

namespace Auth\Model;

use Zend\Authentication\Storage;

class AuthStorage extends Storage\Session {

    public function setRememberMe($rememberMe = 0, $time = 1209600)
    {
        if ($rememberMe == 1) {
            $this->session->getManager()->rememberMe($time);
        }
    }

    public function forgetMe()
    {
        $this->session->getManager()->forgetMe();
    }
} 