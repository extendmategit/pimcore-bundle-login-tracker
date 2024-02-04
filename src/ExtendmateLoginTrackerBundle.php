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

namespace Extendmate\Pimcore\LoginTracker;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;
use Pimcore\Extension\Bundle\Installer\InstallerInterface;
use Pimcore\Extension\Bundle\PimcoreBundleAdminClassicInterface;
use Pimcore\Extension\Bundle\Traits\BundleAdminClassicTrait;

class ExtendmateLoginTrackerBundle extends AbstractPimcoreBundle implements PimcoreBundleAdminClassicInterface
{
    use BundleAdminClassicTrait;

    public const BUNDLE_NAME = 'ExtendmateLoginTrackerBundle';
    public const  TABLE_LOGIN_ATTEMPTS = 'bundle_extendmate_login_tracker_login_attempts';

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function getJsPaths(): array
    {
        return [
            '/bundles/extendmatelogintracker/js/pimcore/startup.js'
        ];
    }

    public function getInstaller(): InstallerInterface
    {
        return $this->container->get(Installer::class);
    }
}
