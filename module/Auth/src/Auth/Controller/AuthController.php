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
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Debug\Debug;

use Auth\Form\LoginForm;
use Auth\Model\LoginFilter;

class AuthController extends AbstractActionController
{
    protected $form;
    protected $storage;
    protected $authservice;


    public function indexAction()
    {
        $this->form = $this->getForm();
        //we can get the form and edit its properties
        //$form->get('submit')->setValue('Add');
        $request = $this->getRequest();

        if ($request->isPost()) {
            $loginFilter = new LoginFilter();
            $this->form->setInputFilter($loginFilter->getInputFilter());
            $this->form->setData($request->getPost());

            if ($this->form->isValid()) {
                $loginFilter->exchangeArray($this->form->getData());
                Debug::dump("Logged in");
                $validatedData = $this->form->getData();
                Debug::dump($validatedData);

            }else{
                Debug::dump("Don't logged");
                $messages = $this->form->getMessages();
                Debug::dump($messages);
            }
        }
        return array('form' => $this->form);
    }

    public function loginAction()
    {
        //if already login, redirect to success page
        if ($this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('success');
        }
        $form       = $this->getForm();
        return array(
            'form'      => $form,
            'messages'  => $this->flashmessenger()->getMessages()
        );
    }

    public function authenticateAction()
    {
        $form       = $this->getForm();
        $redirect = 'auth';

        $request = $this->getRequest();



        $loginFilter = new LoginFilter();
        $this->form->setInputFilter($loginFilter->getInputFilter());
        $this->form->setData($request->getPost());


        if ($request->isPost()){
            $form->setData($request->getPost());
            if ($form->isValid()){
                $loginFilter->exchangeArray($this->form->getData());
                $validatedData = $this->form->getData();
//                Debug::dump($validatedData);
                $this->getAuthService()->getAdapter()
                    ->setIdentity($validatedData['user'])
                    ->setCredential($validatedData['password']);
                $result = $this->getAuthService()->authenticate();

                foreach($result->getMessages() as $message)
                {
                    $this->flashmessenger()->addMessage($message);
                }

                if ($result->isValid()) {
                    $redirect = 'success';
                    //check if it has rememberMe :
                    if ($request->getPost('rememberme') == 1 ) {
                        $this->getSessionStorage()
                            ->setRememberMe(1);
                        //set storage again
                        $this->getAuthService()->setStorage($this->getSessionStorage());
                    }
                    $this->getAuthService()->getStorage()->write($request->getPost('user'));
                }
            }else{
                $messagesErr = $this->form->getMessages();
                foreach($messagesErr as $index => $messages)
                {
                    foreach($messages as $message){
                        $this->flashmessenger()->addMessage($index . ': ' . $message);
                    }

                }
                //Debug::dump($messages);
            }
        }

        return $this->redirect()->toRoute($redirect);
    }

    public function logoutAction()
    {
        $this->getSessionStorage()->forgetMe();
        $this->getAuthService()->clearIdentity();

        $this->flashmessenger()->addMessage("You've been logged out");
        return $this->redirect()->toRoute('auth');
    }

    public function getAuthService()
    {
        if (! $this->authservice) {
            $this->authservice = $this->getServiceLocator()
                ->get('AuthService');
        }

        return $this->authservice;
    }

    public function getSessionStorage()
    {
        if (! $this->storage) {
            $this->storage = $this->getServiceLocator()
                 ->get('Auth\Model\AuthStorage');
        }

        return $this->storage;
    }

    public function getForm()
    {
        if (!$this->form) {
            $this->form = new LoginForm();
        }
        return $this->form;
    }
}