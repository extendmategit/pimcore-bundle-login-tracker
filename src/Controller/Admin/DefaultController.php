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

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AdminAbstractController
{
    #[Route('/', name: 'extendmate_login_tracker_bundle_admin_default')]
    public function indexAction(): Response
    {
        return $this->adminJson(['message' => 'last seen time updated!']);
    }
}
