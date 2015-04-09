<?php

namespace LB\FrameworkExtraBundle\Configuration;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;

/**
 * @Annotation
 */
class Service implements ConfigurationInterface
{
    private $aliases = [];

    public function __construct(array $data)
    {
        if (isset($data['value'])) {
            $data = (array) $data['value'];
        }

        foreach ($data as $key => $value) {
            $this->aliases[is_numeric($key) ? $value : $key] = $value;
        }
    }

    /**
     * @return array
     */
    public function getAliases()
    {
        return $this->aliases;
    }

    public function getAliasName()
    {
        return 'services';
    }

    public function allowArray()
    {
        return true;
    }
}
