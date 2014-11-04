<?php
namespace FzyAuth\Service\AclEnforcer;

use Zend\Http\Response;
use Zend\Mvc\MvcEvent;

class Api extends Base {

    /**
     * @param MvcEvent $e
     *
     * @return mixed
     */
    public function handleRouteMissing( MvcEvent $e )
    {
		return $this->triggerStatus($e, Response::STATUS_CODE_404);
    }

    /**
     * @param MvcEvent $e
     *
     * @return mixed
     */
    public function handleAllowed( MvcEvent $e )
    {
		// do nothing
    }

    /**
     * @param MvcEvent $e
     *
     * @return mixed
     */
    public function handleNotAllowed( MvcEvent $e )
    {
	    return $this->triggerStatus($e, Response::STATUS_CODE_403);
    }
}