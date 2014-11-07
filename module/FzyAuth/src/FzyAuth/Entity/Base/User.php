<?php

namespace FzyAuth\Entity\Base;


use FzyAuth\Entity\Base\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use FzyCommon\Entity\BaseInterface;
use Zend\Form\Annotation;


/**
 *
 * @ORM\Entity
 * @ORM\Table(name="user")
 *
 * @Annotation\Options({
 *      "autorender": {
 *          "ngModel": "user",
 *          "fieldsets": {
 *              {
 *                  "name": \Application\Annotation\FieldSet::DEFAULT_NAME
 *              }
 *          }
 *      }
 * })
 */
class User extends AbstractUser {


}