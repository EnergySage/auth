<?php
namespace FzyAuth\Util\Acl;

use FzyAuth\Exception\Acl\MalformedResource as MalformedResourceException;
use FzyAuth\Service\AclEnforcerInterface;
use FzyCommon\Util\Params;

class Resource
{
    const KEY_RESOURCE = 'resource';
    const KEY_CONTROLLER = 'controller';
    const KEY_ROUTE = 'route';

    const KEY_ACTIONS = 'actions';
    const KEY_PRIVILEGES = 'privileges';

    protected $resource;
    protected $privileges;

    public function __construct($resource, $privileges = null)
    {
        $this->resource = $resource;
        $this->privileges = $privileges;
    }

    /**
     * @return null
     */
    public function getPrivileges()
    {
        return $this->privileges;
    }

    /**
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param Params $params
     *
     * @return Resource
     * @throws MalformedResourceException
     */
    public static function create(Params $params)
    {
        if ($params->has(static::KEY_RESOURCE)) {
            return new Resource($params->get(static::KEY_RESOURCE), $params->get(static::KEY_PRIVILEGES));
        } elseif ($params->has(static::KEY_CONTROLLER)) {
            return new Resource(AclEnforcerInterface::RESOURCE_CONTROLLER_PREFIX.$params->get(static::KEY_CONTROLLER), $params->get(static::KEY_ACTIONS));
        } elseif ($params->has(static::KEY_ROUTE)) {
            return new Resource(AclEnforcerInterface::RESOURCE_ROUTE_PREFIX.$params->get(static::KEY_ROUTE), $params->get(static::KEY_ACTIONS));
        }
        throw new MalformedResourceException("Invalid ACL resource configuration");
    }
}
