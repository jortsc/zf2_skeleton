<?php
/**
 * Created by PhpStorm.
 * User: jorts
 * Date: 4/19/14
 * Time: 4:19 PM
 */

namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SuccessController  extends AbstractActionController
{
    public function indexAction()
    {
        echo '<p style="background-color: #dca7a7;">' ,  __FILE__ , '</p>';
       /* if (!$this->getServiceLocator()->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('auth');
        }*/


        return new ViewModel();
    }
} 