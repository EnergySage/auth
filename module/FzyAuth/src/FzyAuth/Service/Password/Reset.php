<?php
namespace FzyAuth\Service\Password;

use FzyAuth\Entity\Base\UserInterface;
use FzyAuth\Service\Password;
use Zend\Crypt\Password\Bcrypt;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

/**
 * Class Reset
 * @package FzyAuth\Service\Password
 * Service Key: FzyAuth\Password\Reset
 */
class Reset extends Password implements EventManagerAwareInterface
{
    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * Handle
     * @param  UserInterface $user
     * @return mixed
     */
    protected function process(UserInterface $user)
    {
        return $this->changePassword($user, $this->getOptions()->get('newCredential'));
    }

    protected function changePassword(UserInterface $user, $password)
    {
        $bcrypt = new Bcrypt();
        $bcrypt->setCost($this->getModuleConfig()->get('password_cost', 14));

        $pass = $bcrypt->create($password);
        $user->setPassword($pass);
        $user->setPasswordToken(null);
        // trigger event to allow password reset hooks
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $user));
        $this->em()->flush();
        $this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('user' => $user));

        return true;
    }

    /**
     * Inject an EventManager instance
     *
     * @param  EventManagerInterface $eventManager
     * @return void
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }
}
