<?php
namespace FzyAuth\Service\AclEnforcer;

use FzyAuth\Service\Acl;
use FzyAuth\Service\AclEnforcerInterface;
use FzyCommon\Util\Params;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ModelInterface;

abstract class Base extends \FzyAuth\Service\Base implements AclEnforcerInterface {

    protected $acl;

    /**
     * Do any setup
     *
     * @param MvcEvent $e
     *
     * @return mixed
     */
    public function init( MvcEvent $e )
    {

    }

    /**
     * Add acl to the view model
     *
     * @param ModelInterface $viewModel
     *
     * @return mixed
     */
    public function attachToView( ModelInterface $viewModel )
    {
        $viewModel->setVariable('acl', $this->getAcl());
        return $this;
    }

    /**
     * @return \Zend\Permissions\Acl\Acl
     */
    public function getAcl()
    {
        if (!isset($this->acl)) {
            $serviceLocator = $this->getServiceLocator();
            $aclConfig = $this->getModuleConfig()->getWrapped('acl');
            /* @var $aclFactory \FzyAuth\Factory\Acl */
            $aclFactory = $this->getServiceLocator()->get($this->getModuleConfig()->get('acl_factory', 'FzyAuth\Factory\Acl'));
            $this->acl = $aclFactory->createAcl($aclConfig, $serviceLocator);
        }
        return $this->acl;
    }


    /**
     * Abstraction to use current user role (guest if not logged in)
     *
     * @param $resource
     * @param null $privilege
     *
     * @return mixed
     */
    public function isAllowed( $resource, $privilege = null )
    {
        $role = $this->getServiceLocator()->get('FzyAuth\Role\Guest');
        $zfcAuth = $this->getServiceLocator()->get('zfcuser_auth_service');
        if ($zfcAuth->hasIdentity()) {
            $role = $zfcAuth->getIdentity()->getRole();
        }
        return $this->getAcl()->isAllowed($role, $resource, $privilege);
    }

    /**
     * @param MvcEvent $e
     * @param $routeName
     * @param $action
     *
     * @return mixed
     */
    public function isAllowedToRoute( MvcEvent $e, $routeName, $action )
    {
        return $this->isAllowed($routeName, $action);
    }

    /**
     * @param $routeName
     *
     * @return mixed
     */
    public function hasRoute( $routeName )
    {
        return $this->getAcl()->hasResource($routeName);
    }


}