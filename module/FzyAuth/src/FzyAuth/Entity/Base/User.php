<?php

namespace FzyAuth\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
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
 *                  "name": \FzyForm\Annotation\FieldSet::DEFAULT_NAME
 *              }
 *          }
 *      }
 * })
 */
class User extends AbstractUser
{
}
