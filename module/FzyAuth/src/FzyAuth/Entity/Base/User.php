<?php

namespace FzyAuth\Entity\Base;


use FzyAuth\Entity\Base\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use FzyCommon\Entity\BaseInterface;
use FzyCommon\Entity\type;


/**
 *
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends AbstractUser {


}