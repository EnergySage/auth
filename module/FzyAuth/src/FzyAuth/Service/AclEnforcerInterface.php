<?php
namespace FzyAuth\Service;

use Zend\Mvc\MvcEvent;
use Zend\View\Model\ModelInterface;

interface AclEnforcerInterface {

	/**
	 * Do any setup
	 *
	 * @param MvcEvent $e
	 *
	 * @return mixed
	 */
	public function init(MvcEvent $e);

	/**
	 * Add acl to the view model
	 * @param ModelInterface $viewModel
	 *
	 * @return mixed
	 */
	public function attachToView(ModelInterface $viewModel);

	/**
	 * @return Acl object
	 */
	public function getAcl();

	/**
	 * Abstraction to use current user role (guest if not logged in)
	 * @param $resource
	 * @param null $privilege
	 *
	 * @return mixed
	 */
	public function isAllowed($resource, $privilege = null);

	/**
	 * @param MvcEvent $e
	 * @param $routeName
	 * @param $action
	 *
	 * @return mixed
	 */
	public function isAllowedToRoute(MvcEvent $e, $routeName, $action);

	/**
	 * @param $routeName
	 *
	 * @return mixed
	 */
	public function hasRoute(MvcEvent $e, $routeName);

    /**
     * @param MvcEvent $e
     *
     * @return mixed
     */
	public function handleRouteMissing(MvcEvent $e);

    /**
     * @param MvcEvent $e
     *
     * @return mixed
     */
    public function handleAllowed(MvcEvent $e);

    /**
     * @param MvcEvent $e
     *
     * @return mixed
     */
	public function handleNotAllowed(MvcEvent $e);

}