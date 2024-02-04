<?php
declare(strict_types=1);

/**
 *
 * @category   Pimcore
 * @package    Extendmate
 * @subpackage LoginTracker
 *
 * @author     Faiyaz Alam
 *
 * @link       https://github.com/faiyazalam
 */

namespace Extendmate\Pimcore\LoginTracker\Model;

use Pimcore\Model\AbstractModel;
use Pimcore\Model\Exception\NotFoundException;

class LoginAttempt extends AbstractModel
{
    public const STATUS_LOGIN = 'login';
    public const STATUS_LOGOUT = 'logout';
    public const STATUS_FAIL = 'fail';
    public const STATUS_ERROR = 'error';

    public ?int $id = null;
    public ?int $userId = null;
    public ?string $username = null;
    public ?string $roles = null;
    public int $loginAt;
    public ?int $logoutAt = null;
    public ?int $lastSeenAt = null;
    public ?string $ipAddress = null;
    public ?string $userAgent = null;
    public ?string $firewallName = null;
    public ?bool $isAdmin = false;
    public ?string $status = null;

    public static function getById(int $id): ?self
    {
        try {
            $obj = new self;
            $obj->getDao()->getById($id);

            return $obj;
        } catch (NotFoundException) {
            \Pimcore\Logger::warn(sprintf('LoginAttempt with id %d not found', $id));
        }

        return null;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of userId
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set the value of userId
     *
     * @return  self
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get the value of username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of roles
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set the value of roles
     *
     * @return  self
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get the value of loginAt
     */
    public function getLoginAt()
    {
        return $this->loginAt;
    }

    /**
     * Set the value of loginAt
     *
     * @return  self
     */
    public function setLoginAt($loginAt)
    {
        $this->loginAt = $loginAt;

        return $this;
    }

    /**
     * Get the value of logoutAt
     */
    public function getLogoutAt()
    {
        return $this->logoutAt;
    }

    /**
     * Set the value of logoutAt
     *
     * @return  self
     */
    public function setLogoutAt($logoutAt)
    {
        $this->logoutAt = $logoutAt;

        return $this;
    }

    /**
     * Get the value of lastSeenAt
     */
    public function getLastSeenAt()
    {
        return $this->lastSeenAt;
    }

    /**
     * Set the value of lastSeenAt
     *
     * @return  self
     */
    public function setLastSeenAt($lastSeenAt)
    {
        $this->lastSeenAt = $lastSeenAt;

        return $this;
    }

    /**
     * Get the value of ipAddress
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Set the value of ipAddress
     *
     * @return  self
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get the value of userAgent
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Set the value of userAgent
     *
     * @return  self
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * Get the value of status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of firewallName
     *
     */
    public function getFirewallName()
    {
        return $this->firewallName;
    }

    /**
     * Set the value of firewallName
     *
     * @return  self
     */
    public function setFirewallName($firewallName)
    {
        $this->firewallName = $firewallName;

        return $this;
    }

    /**
     * Get the value of isAdmin
     */
    public function getIsAdmin(): bool
    {
        return (bool)$this->isAdmin;
    }

    /**
     * Set the value of isAdmin
     *
     * @return  self
     */
    public function setIsAdmin(bool $isAdmin)
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }
}
