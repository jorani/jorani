<?php
/**
 * Doctrine Bridge Library for CodeIgniter 3
 * This library initializes the Doctrine EntityManager using CI3 database settings.
 */

use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

/**
 * This class is a bridge between Doctrine and CodeIgniter 3
 * @license http://opensource.org/licenses/MIT MIT
 * @since   1.0.5
 */
class Doctrine
{

    /**
     * @var EntityManager The core Doctrine service
     */
    public $em;

    public function __construct()
    {
        // 1. Load CodeIgniter's database configuration
        if (!file_exists($file_path = APPPATH . 'config/database.php')) {
            throw new \Exception('CodeIgniter database config not found.');
        }
        include $file_path;

        // 2. Define paths to your Entities
        $paths = [APPPATH . 'Entity'];
        $isDevMode = (ENVIRONMENT === 'development');

        // 3. Map CI3 Database array to Doctrine Connection parameters
        // We assume the 'default' group is used
        $db_params = $db['default'];

        $connectionParams = [
            'driver' => 'pdo_mysql',
            'user' => $db_params['username'],
            'password' => $db_params['password'],
            'host' => $db_params['hostname'],
            'dbname' => $db_params['database'],
            'charset' => $db_params['char_set']
        ];

        // 4. Set up Metadata Configuration (Using PHP 8 Attributes)
        // This is the standard for modern Symfony applications
        $config = ORMSetup::createAttributeMetadataConfiguration($paths, false);
        $cache = new FilesystemAdapter(
            namespace: 'doctrine',
            defaultLifetime: 0,
            directory: APPPATH . 'cache/doctrine'
        );

        $config->setMetadataCache($cache);
        $config->setQueryCache($cache);

        // 5. Initialize the Connection and Entity Manager
        $connection = DriverManager::getConnection($connectionParams, $config);
        $this->em = new EntityManager($connection, $config);
    }
}
