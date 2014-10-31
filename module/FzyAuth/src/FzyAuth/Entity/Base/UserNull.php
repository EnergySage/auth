<?php
namespace Application\Entity\Base;

use FzyCommon\Entity\BaseNull as Entity;
use Application\Service\Acl;

class UserNull extends Entity implements UserInterface
{
    /**
     * @param int $loginAttempts
     */
    public function setLoginAttempts($loginAttempts)
    {
        return $this;
    }

    /**
     * @return int
     */
    public function getLoginAttempts()
    {
        return null;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return null;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return null;
    }

    /**
     * @param string $userName
     */
    public function setUserName($userName)
    {
        return $this;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return null;
    }

    /**
     * @param $email
     * @return $this
     */
    public function setEmail($email)
    {
        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return UserInterface::STATE_INACTIVE;
    }

    /**
     * @param $state
     * @return $this
     */
    public function setState($state)
    {
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedTs()
    {
        return new \DateTime();
    }

    /**
     * @param $ts
     * @return $this
     */
    public function setCreatedTs($ts)
    {
        return $this;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return null;
    }

    /**
     * Set id.
     *
     * @param  int   $id
     * @return $this
     */
    public function setId($id)
    {
        return $this;
    }

    /**
     * Get displayName.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return null;
    }

    /**
     * Set displayName.
     *
     * @param  string $displayName
     * @return $this
     */
    public function setDisplayName($displayName)
    {
        return $this;
    }

    /**
     * Get password.
     *
     * @return string password
     */
    public function getPassword()
    {
        return null;
    }

    /**
     * Set password.
     *
     * @param  string $password
     * @return $this
     */
    public function setPassword($password)
    {
        return $this;
    }

    /**
     * @param string $passwordToken
     */
    public function setPasswordToken($passwordToken)
    {
        return $this;
    }

    /**
     * @return string
     */
    public function getPasswordToken()
    {
        return null;
    }

    /**
     * @param  RoleInterface $role
     * @return $this
     */
    public function getRole()
    {
        return $this;
    }

    /**
     * @param  array $roles
     * @return $this
     */
    public function setRole($role)
    {
        return $this;
    }


    /**
     * @return \DateTime|null
     */
    public function getUpdatedTs()
    {
        return null;
    }

    /**
     * @param $createdTs
     * @return $this
     */
    public function setUpdatedTs($updatedTs)
    {
        return $this;
    }

}
