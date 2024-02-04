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

use Doctrine\DBAL\Connection;
use Exception;
use Extendmate\Pimcore\LoginTracker\Migrations\Version20231202000000;
use Pimcore\Extension\Bundle\Installer\SettingsStoreAwareInstaller;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class Installer extends SettingsStoreAwareInstaller
{
    private readonly ?string $pimcoreCustomReportsDirectory;

    private readonly ?string $bundleCustomReportsDirectory;

    public function __construct(BundleInterface $bundle, private ParameterBagInterface $parameterBag)
    {
        parent::__construct($bundle);
        $this->pimcoreCustomReportsDirectory = $this->findPimcoreCustomReportsDirectory();
        $this->bundleCustomReportsDirectory =  __DIR__ . '/var/config/custom_reports/';
    }

    private function findPimcoreCustomReportsDirectory(): string | null
    {
        if (!$this->parameterBag->has('pimcore_custom_reports.config_location')) {
            return null;
        }
        $config = $this->parameterBag->get('pimcore_custom_reports.config_location');
        if (isset($config['custom_reports']['write_target']['options']['directory'])) {
            $customReportsDirectory = $config['custom_reports']['write_target']['options']['directory'];
            $customReportsDirectory = rtrim($customReportsDirectory, '/');
            $customReportsDirectory = $customReportsDirectory . '/';

            return $customReportsDirectory;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function install(): void
    {
        $this->createCustomReports();
        $this->createTables();
        parent::install();
    }

    /**
     * {@inheritdoc}
     */
    public function needsReloadAfterInstall(): bool
    {
        return true;
    }

    protected function getDb(): Connection
    {
        return \Pimcore\Db::get();
    }

    private function createCustomReports(): void
    {
        if (is_null($this->pimcoreCustomReportsDirectory)) {
            return;
        }
        if (!file_exists($this->pimcoreCustomReportsDirectory)) {
            if (!mkdir($this->pimcoreCustomReportsDirectory, 0777, true)) {
                throw new Exception(sprintf("Failed to create directory: '%s'", $this->pimcoreCustomReportsDirectory));
            }
        }

        $files = scandir($this->bundleCustomReportsDirectory);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $sourceFile = $this->bundleCustomReportsDirectory . $file;
                $targetFile = $this->pimcoreCustomReportsDirectory . $file;
                if (!copy($sourceFile, $targetFile)) {
                    throw new Exception(sprintf(
                        "Failed to copy the file from '%s' to '%s'",
                        $sourceFile,
                        $targetFile
                    ));
                }
            }
        }
    }

    private function deleteCustomReports(): void
    {
        if (is_null($this->pimcoreCustomReportsDirectory)) {
            return;
        }

        if (!file_exists($this->pimcoreCustomReportsDirectory)) {
            return;
        }

        $files = scandir($this->bundleCustomReportsDirectory);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $targetFile = $this->pimcoreCustomReportsDirectory . $file;
                if (!file_exists($targetFile)) {
                    continue;
                }

                if (!unlink($targetFile)) {
                    throw new Exception(sprintf(
                        "Failed to delete the file '%s'",
                        $targetFile
                    ));
                }
            }
        }
    }

    protected function createTables(): void
    {
        $db = $this->getDb();

        $db->executeQuery('CREATE TABLE IF NOT EXISTS `' . ExtendmateLoginTrackerBundle::TABLE_LOGIN_ATTEMPTS . "` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `userId` int(11) unsigned DEFAULT NULL,
            `username` varchar(100) DEFAULT NULL,
            `roles` varchar(1000) DEFAULT NULL,
            `ipAddress` varchar(50) DEFAULT NULL,
            `userAgent` varchar(100) DEFAULT NULL,
            `lastSeenAt` int(11) unsigned DEFAULT NULL,
            `loginAt` int(11) unsigned DEFAULT NULL,
            `logoutAt` int(11) unsigned DEFAULT NULL,
            `firewallName` varchar(255) DEFAULT NULL,
            `isAdmin` tinyint(1) unsigned DEFAULT 0,
            `status` enum('login','logout','fail', 'error') NOT NULL,
            PRIMARY KEY (`id`),
            KEY `userId` (`userId`),
            KEY `username` (`username`),
            KEY `ipAddress` (`ipAddress`),
            KEY `loginAt` (`loginAt`),
            KEY `logoutAt` (`logoutAt`),
            KEY `lastSeenAt` (`lastSeenAt`),
            KEY `firewallName` (`firewallName`),
            KEY `isAdmin` (`isAdmin`),
            KEY `status` (`status`),
            CONSTRAINT `" . ExtendmateLoginTrackerBundle::TABLE_LOGIN_ATTEMPTS . '_userId` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
    }

    private function deleteTables(): void
    {
        $this->getDb()->executeQuery(sprintf('DROP TABLE IF EXISTS %s', ExtendmateLoginTrackerBundle::TABLE_LOGIN_ATTEMPTS));
    }

    /**
     * {@inheritdoc}
     */
    public function uninstall(): void
    {
        $this->deleteCustomReports();
        $this->deleteTables();
        parent::uninstall();
    }

    /**
     * {@inheritdoc}
     */
    public function getLastMigrationVersionClassName(): ?string
    {
        return Version20231202000000::class;
    }
}
