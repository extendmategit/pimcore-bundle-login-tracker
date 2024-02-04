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

namespace Extendmate\Pimcore\LoginTracker\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Extendmate\Pimcore\LoginTracker\ExtendmateLoginTrackerBundle;
use Pimcore\Migrations\BundleAwareMigration;

final class Version20231202000000 extends BundleAwareMigration
{
    protected function getBundleName(): string
    {
        return ExtendmateLoginTrackerBundle::BUNDLE_NAME;
    }

    public function up(Schema $schema): void
    {
        //do nothing!

    }

    public function down(Schema $schema): void
    {
        //do nothing!
    }
}
