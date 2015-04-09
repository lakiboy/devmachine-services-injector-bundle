<?php

namespace LB\FrameworkExtraBundle\ServiceLocator;

interface ServiceLocator
{
    /**
     * @param string $id
     *
     * @return object
     */
    public function get($id);
}
