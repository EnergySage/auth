<?php
namespace FzyAuth\Factory;

use FzyCommon\Util\Params;
use \Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\ServiceManager\ServiceLocatorInterface;

class Acl {

    /**
     * @param Params $roleConfig
     *
     * @return ZendAcl
     */
    public function createAcl(Params $roleConfig, ServiceLocatorInterface $sm)
    {
        $acl = new ZendAcl();
        // add all roles from config
        foreach ($roleConfig->get('roles') as $roleName => $roleData) {
            $roleMap = Params::create($roleData);
            $role = new \Zend\Permissions\Acl\Role\GenericRole($roleName);
            $acl->addRole($role, $roleMap->get('inherits', array()));
            // add resources from config
            foreach ($roleMap->get('allow', array()) as $resourceData) {
                $resourceMap = Params::create($resourceData);
                $resource = $resourceMap->get('resource');
                $this->addAclResource($acl, $resource)
                    ->addAllowedResource($acl, $role, $resource, $resourceMap->get('privileges'));
            }
            // add denies
            foreach ($roleMap->get('deny', array()) as $resourceData) {
                $resourceMap = Params::create($resourceData);
                $resource = $resourceMap->get('resource');
                $this->addAclResource($acl, $resource)
                    ->addDeniedResource($acl, $role, $resource, $resourceMap->get('privileges'));
            }
        }

        // trigger event for post-resource setup
        return $acl;
    }

    /**
     * @param Acl $acl
     * @param $role
     * @param $resource
     * @param $privileges
     */
    protected function addAllowedResource(ZendAcl $acl, $role, $resource, $privileges)
    {
        $acl->allow($role, $resource, $privileges);
        return $this;
    }

    /**
     * @param Acl $acl
     * @param $role
     * @param $resource
     * @param $privileges
     */
    protected function addDeniedResource(ZendAcl $acl, $role, $resource, $privileges)
    {
        $acl->deny($role, $resource, $privileges);
        return $this;
    }

    /**
     * @param Acl $acl
     * @param $resource
     */
    protected function addAclResource(ZendAcl $acl, $resource)
    {
        if (!$acl->hasResource($resource)) {
            $acl->addResource(new \Zend\Permissions\Acl\Resource\GenericResource($resource));
        }
        return $this;
    }

}