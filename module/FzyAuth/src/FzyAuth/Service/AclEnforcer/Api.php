<?php
namespace FzyAuth\Service\AclEnforcer;

use Zend\Mvc\MvcEvent;

class Api extends Base {

    /**
     * @param MvcEvent $e
     *
     * @return mixed
     */
    public function handleRouteMissing( MvcEvent $e )
    {

    }

    /**
     * @param MvcEvent $e
     *
     * @return mixed
     */
    public function handleAllowed( MvcEvent $e )
    {

    }

    /**
     * @param MvcEvent $e
     *
     * @return mixed
     */
    public function handleNotAllowed( MvcEvent $e )
    {

    }
}