<?php
namespace FzyAuth\Listener;

use FzyAuth\Entity\Base\UserInterface;
use Zend\Mvc\MvcEvent;

class Register extends Base
{
    public function latch(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $em           = $eventManager->getSharedManager();

        $zfcServiceEvents = $e->getApplication()->getServiceManager()->get('zfcuser_user_service')->getEventManager();
        $zfcServiceEvents->attach('register', function ($e) {
            $form = $e->getParam('form');
            /* @var $user \FzyAuth\Entity\Base\UserInterface */
            $user = $e->getParam('user');
            $user->setRole(UserInterface::ROLE_USER);
        });

    }
}
