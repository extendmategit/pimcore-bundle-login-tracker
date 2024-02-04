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

use Pimcore\Controller\Traits\JsonHelperTrait;
use Pimcore\Controller\UserAwareController;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AdminAbstractController extends UserAwareController
{
    use JsonHelperTrait;

    /**
     * Returns a JsonResponse that uses the admin serializer
     */
    protected function adminJson(mixed $data, int $status = 200, array $headers = [], array $context = [], bool $useAdminSerializer = true): JsonResponse
    {
        return $this->jsonResponse($data, $status, $headers, $context, $useAdminSerializer);
    }
}
