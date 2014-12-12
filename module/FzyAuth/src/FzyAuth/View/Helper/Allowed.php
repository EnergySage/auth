<?php
namespace FzyAuth\View\Helper;

use FzyCommon\View\Helper\Base;

class Allowed extends Base
{
    public function __invoke($resource, $privilege = null)
    {
        /* @var $acl \Zend\Permissions\Acl\Acl */
        $acl = $this->getService('FzyAuth\Acl');
        /* @var $user \FzyAuth\Entity\Base\UserInterface */
        $user = $this->getService('FzyAuth\CurrentUser');

        return $acl->isAllowed($user->getRole(), $resource, $privilege);
    }
}
