<?php
namespace App\Traits;

/**
 * Trait to provide Doctrine and Service capabilities to any Controller.
 * This avoids bloating your existing MY_Controller.
 */
trait DoctrineBridge
{
    /** @var \Doctrine\ORM\EntityManager|null */
    protected $em;

    /** @var array Cache for service instances */
    private $serviceCache = [];

    /**
     * Initializes the Doctrine connection.
     * Call this once in your existing MY_Controller constructor.
     * @return void
     */
    protected function initDoctrine(): void
    {
        // Access the CI instance to load the library
        $ci =& get_instance();
        $ci->load->library('doctrine');
        $this->em = $ci->doctrine->em;
    }

    /**
     * Fetch and initialize a Service from application/src/Service
     * @template T
     * @param class-string<T> $serviceClass
     * @return T
     */
    protected function getService(string $serviceClass)
    {
        if (isset($this->serviceCache[$serviceClass])) {
            return $this->serviceCache[$serviceClass];
        }
        if (!class_exists($serviceClass)) {
            throw new \RuntimeException("Service [$serviceClass] not found.");
        }
        // We inject the EntityManager directly into the service
        $instance = new $serviceClass($this->em);
        return $this->serviceCache[$serviceClass] = $instance;
    }
}
