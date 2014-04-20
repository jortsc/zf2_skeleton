<?php
/**
 * Created by PhpStorm.
 * User: jorts
 * Date: 4/19/14
 * Time: 4:19 PM
 */

namespace Reports\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Debug\Debug;

class DashboardController  extends AbstractActionController
{
    public function indexAction()
    {
        $authService = $this->getServiceLocator()
            ->get('AuthService');

        Debug::dump($authService->getIdentity());
        return new ViewModel();
    }
}