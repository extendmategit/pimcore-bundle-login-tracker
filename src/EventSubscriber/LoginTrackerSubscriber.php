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

namespace Extendmate\Pimcore\LoginTracker\EventSubscriber;

use Extendmate\Pimcore\LoginTracker\Model as BundleModel;
use Pimcore\Model\User\Role;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\TooManyLoginAttemptsAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LoginTrackerSubscriber implements EventSubscriberInterface
{
    private const  SESSION_ROW_ID_KEY = 'bundle_emulh_row_id';

    public function __construct(private UrlGeneratorInterface $urlGeneratorInterface)
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccess',
            LoginFailureEvent::class => 'onLoginFailure',
            LogoutEvent::class => ['onLogout', 99],
            KernelEvents::RESPONSE => 'onKernelResponse'
        ];
    }

    public function onLogout(LogoutEvent $event)
    {
        $request = $event->getRequest();
        $rowId = $request->getSession()->get(self::SESSION_ROW_ID_KEY);
        if (!$rowId) {
            return;
        }

        $loginAttempt = BundleModel\LoginAttempt::getById($rowId);
        if (!$loginAttempt instanceof BundleModel\LoginAttempt) {
            return;
        }

        $timestamp = time();
        $loginAttempt->setStatus(BundleModel\LoginAttempt::STATUS_LOGOUT);
        $loginAttempt->setLogoutAt($timestamp);
        $loginAttempt->setLastSeenAt($timestamp);
        try {
            $loginAttempt->save();
        } catch (\Exception $ex) {
            \Pimcore\Logger::critical($ex->getMessage(), [
                'filepath' => __FILE__,
                'line' => __LINE__,
                'record_id' => $loginAttempt->getId(),
                'operation' => 'saving_logout_activity'
            ]);
        }
    }

    public function onLoginFailure(LoginFailureEvent $event)
    {
        $request = $event->getRequest();
        $passport = $event->getPassport();

        $loginAttempt = new BundleModel\LoginAttempt();
        $loginAttempt->setUsername($passport->getBadge(UserBadge::class)->getUserIdentifier());

        try {
            $user = $passport->getUser();
        } catch (\Symfony\Component\Security\Core\Exception\UserNotFoundException) {
            $user = null;
        }

        if ($user instanceof \Pimcore\Security\User\User) {
            $pimuser = $user->getUser();
            if ($pimuser instanceof \Pimcore\Model\User) {
                $loginAttempt->setUsername($pimuser->getName());
                $loginAttempt->setUserId($pimuser->getId());
                $loginAttempt->setIsAdmin($pimuser->getAdmin());
            }
        }

        $loginAttempt->setIpAddress($request->getClientIp());
        $loginAttempt->setUserAgent($request->headers->get('user-agent'));
        $loginAttempt->setLoginAt(time());
        $loginAttempt->setStatus($event->getException() instanceof TooManyLoginAttemptsAuthenticationException ? BundleModel\LoginAttempt::STATUS_ERROR : BundleModel\LoginAttempt::STATUS_FAIL);
        try {
            $loginAttempt->save();
        } catch (\Exception $ex) {
            \Pimcore\Logger::critical($ex->getMessage(), [
                'filepath' => __FILE__,
                'line' => __LINE__,
                'username' => $loginAttempt->getUsername(),
                'operation' => 'saving_login_failure_activity'
            ]);
        }
    }

    public function onLoginSuccess(LoginSuccessEvent $event)
    {
        $request = $event->getRequest();
        $user = $event->getPassport()->getUser()->getUser();
        $loginAttempt = new BundleModel\LoginAttempt();

        $roleIds = $user->getRoles();
        if ($roleIds) {
            $roleIds = array_map('intval', $roleIds);
            $roleIdsString = implode(',', $roleIds);
            $roles = new Role\Listing();
            $roles->addConditionParam("id IN ($roleIdsString)");

            $roleNames = [];
            foreach ($roles as $role) {
                $roleNames[] = $role->getName();
            }
            if (!empty($roleNames)) {
                $loginAttempt->setRoles(implode(',', $roleNames));
            }
        }
        $timestamp = time();
        $loginAttempt->setUserId($user->getId());
        $loginAttempt->setUsername($user->getName());
        $loginAttempt->setIpAddress($request->getClientIp());
        $loginAttempt->setUserAgent($request->headers->get('user-agent'));
        $loginAttempt->setLoginAt($timestamp);
        $loginAttempt->setLastSeenAt($timestamp);
        $loginAttempt->setFirewallName($event->getFirewallName());
        $loginAttempt->setIsAdmin($user->getAdmin());
        $loginAttempt->setStatus(BundleModel\LoginAttempt::STATUS_LOGIN);
        try {
            $loginAttempt->save();
            $request->getSession()->set(self::SESSION_ROW_ID_KEY, $loginAttempt->getId());
        } catch (\Exception $ex) {
            \Pimcore\Logger::critical($ex->getMessage(), [
                'filepath' => __FILE__,
                'line' => __LINE__,
                'username' => $loginAttempt->getUsername(),
                'operation' => 'saving_login_success_activity'
            ]);
        }
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $request = $event->getRequest();

        if ($this->urlGeneratorInterface->generate('extendmate_login_tracker_bundle_admin_default') !== $request->getPathInfo()) {
            return;
        }

        $rowId = $request->getSession()->get(self::SESSION_ROW_ID_KEY);
        if (!$rowId) {
            return;
        }

        $loginAttempt = BundleModel\LoginAttempt::getById($rowId);
        if (!$loginAttempt instanceof BundleModel\LoginAttempt) {
            return;
        }

        $loginAttempt->setLastSeenAt(time());

        try {
            $loginAttempt->save();
        } catch (\Exception $ex) {
            \Pimcore\Logger::critical($ex->getMessage(), [
                'filepath' => __FILE__,
                'line' => __LINE__,
                'record_id' => $loginAttempt->getId(),
                'operation' => 'saving_lastseen_activity'
            ]);
        }
    }
}
