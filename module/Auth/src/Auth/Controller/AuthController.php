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
    /**
     * @var LoginForm
    */
    protected $form;
    /**
     * @var AuthStorage
    */
    protected $storage;
    /**
     * @var AuthService
    */
    protected $authService;

    /**
     * Shows the login form or redirects to success
     * if user is already logged
    */
    public function loginAction()
    {
        if ($this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('success');
        }
        echo '<p style="background-color: #dca7a7;">' ,  __FILE__ , '</p>';
        $form       = $this->getForm();
        return array(
            'form'      => $form,
            'messages'  => $this->flashmessenger()->getMessages()
        );
    }

    /**
     * Checks if post params are valid
    */
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
//                    if ($request->getPost('rememberme') == 1) {
//                        $this->getSessionStorage()
//                            ->setRememberMe(1);
//                        //set storage again
//                        $this->getAuthService()->setStorage($this->getSessionStorage());
//                    }
                    $this->getAuthService()->getStorage()->write($validatedData['user']);
                }
            }else{
                $messagesErr = $this->form->getMessages();
                foreach($messagesErr as $index => $messages)
                {
                    foreach($messages as $message){
                        $this->flashmessenger()->addMessage($index . ': ' . $message);
                    }

                }
            }
        }
        return $this->redirect()->toRoute($redirect);
    }

    /**
     * Logs out to user, clears identity, sets message and redirect to route login
     *
     * @return a redirection to login
    */
    public function logoutAction()
    {
        $message = '';
        $this->getSessionStorage()->forgetMe();

        if(true === $this->getAuthService()->hasIdentity()){
            $this->getAuthService()->clearIdentity();
            $message = "You've been logged out";
        }else{
            $message = "Can't access..";
        }

        $this->flashmessenger()->addMessage($message);
        return $this->redirect()->toRoute('auth');
    }

    /**
     * Returns AuthService
     *
     * @return AuthService
    */
    public function getAuthService()
    {
        if (!$this->authService) {
            $this->authService = $this->getServiceLocator()
                ->get('AuthService');
        }
        return $this->authService;
    }

    /**
     * Returns an instance of Auth\Model\AuthStorage
     *
     * @return AuthStorage
    */
    public function getSessionStorage()
    {
        if (!$this->storage) {
            $this->storage = $this->getServiceLocator()
                 ->get('Auth\Model\AuthStorage');
        }
        return $this->storage;
    }

    /**
     * Returns an instance of LoginForm
     *
     * @return LoginForm
    */
    public function getForm()
    {
        if (!$this->form) {
            $this->form = new LoginForm();
        }
        return $this->form;
    }
}