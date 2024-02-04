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

namespace Extendmate\Pimcore\LoginTracker\Service;

class Configuration
{
    public function __construct(private array $config = [])
    {
    }

    public function getLastSeenConfig(): array
    {
        return $this->config['last_seen'];
    }
}
