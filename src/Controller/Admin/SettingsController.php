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

namespace Extendmate\Pimcore\LoginTracker\Controller\Admin;

use Extendmate\Pimcore\LoginTracker\Service\Configuration;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SettingsController extends AdminAbstractController
{
    public function __construct(private Configuration $configuration)
    {
    }

    #[Route('/settings', name: 'extendmate_login_tracker_bundle_admin_settings')]
    public function indexAction(): Response
    {
        return $this->adminJson(['last_seen' => $this->configuration->getLastSeenConfig()]);
    }
}
