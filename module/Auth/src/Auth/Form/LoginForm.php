<?php
/**
 * Created by PhpStorm.
 * User: jorts
 * Date: 4/17/14
 * Time: 11:07 AM
 */
namespace Auth\Form;

use Zend\Form\Form;

class LoginForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('login');

        $this->add(array(
            'name' => 'user',
            'type' => 'Text',
            'options' => array(
                'label' => 'User',
            ),
        ));
        $this->add(array(
            'name' => 'password',
            'type' => 'Password',
            'options' => array(
                'label' => 'Password',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Send',
                'id' => 'submit',
            ),
        ));
    }
}