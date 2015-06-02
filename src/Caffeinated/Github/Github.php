<?php
namespace Caffeinated\Github;

use Caffeinated\Github\Factory;
use GrahamCampbell\Manager\AbstractManager;
use Illuminate\Contracts\Config\Repository;

class Github extends AbstractManager
{
    /**
     * @var Caffeinated\Github\Factory
     */
    protected $factory;

    /**
     * Create a new instance of Github.
     *
     * @param  Illuminate\Contracts\Config\Repository  $config
     * @param  Caffeinated\Github\Factory              $factory
     */
    public function __construct(Repository $config, Factory $factory)
    {
        parent::__construct($config);

        $this->factory = $factory;
    }

    /**
     * Create a new connection to the GitHub API.
     *
     * @param  array  $config
     * @return Caffeinated\Github\Factory
     */
    protected function createConnection(array $config)
    {
        return $this->factory->make($config);
    }

    /**
     * Return the config name.
     *
     * @return  string
     */
    protected function getconfigName()
    {
        return 'github';
    }

    /**
     * Get the factory instance.
     *
     * @return Caffeinated\Github\Factory
     */
    public function getFactory()
    {
        return $this->factory;
    }
}
