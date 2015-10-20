<?php

namespace Devmachine\Bundle\ServicesInjectorBundle\ServiceLocator;

interface ServiceLocator
{
    /**
     * @param string $id
     *
     * @return object
     */
    public function get($id);
}
