<?php
namespace Caffeinated\Github;

use GrahamCampbell\Manager\AbstractManager;
use Illuminate\Contracts\Config\Repository;

class Github extends AbstractManager
{
    protected $factory;

    public function __construct(Repository $config, Factory $factory)
    {
        parent::__construct($config);

        $this->factory = $factory;
    }

    protected function createConnection(array $config)
    {
        return $this->factory->make($config);
    }

    protected function getconfigName()
    {
        return 'github';
    }

    public function getFactory()
    {
        return $this->factory;
    }
}
