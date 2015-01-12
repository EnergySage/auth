<?php

namespace FzyAuth\Service;

use FzyCommon\Service\Base as BaseService;

class Base extends BaseService
{
    const MODULE_CONFIG_KEY = 'fzyauth';

    /**
     * @return \FzyAuth\Entity\Base\UserInterface
     */
    public function getCurrentUser()
    {
        return $this->getServiceLocator()->get('FzyAuth\CurrentUser');
    }
}
