<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Auth\Controller;

use Zend\Mail\Protocol\Smtp\Auth\Login;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Debug\Debug;

use Auth\Form\LoginForm;
use Auth\Model\LoginFilter;

class AuthController extends AbstractActionController
{
    public function indexAction()
    {
        $form = new LoginForm();

        //we can get the form and edit its properties
        //$form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $loginFilter = new LoginFilter();
            $form->setInputFilter($loginFilter->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $loginFilter->exchangeArray($form->getData());
                Debug::dump("Logged in");
                $validatedData = $form->getData();
                Debug::dump($validatedData);
            }else{
                Debug::dump("Don't logged");
                $messages = $form->getMessages();
                Debug::dump($messages);
            }
        }
        return array('form' => $form);
    }
}