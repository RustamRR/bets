<?php

namespace RememberMe\Options;

use GoalioRememberMe\Options\ModuleOptions as BaseModuleOptions;

class ModuleOptions extends BaseModuleOptions
{
    /**
     * @var string
     */
    protected $rememberMeEntityClass = 'RememberMe\Entity\RememberMe';
    /**
     * @var bool
     */
    protected $enableDefaultEntities = false;
    /**
     * @param boolean $enableDefaultEntities
     */
    public function setEnableDefaultEntities($enableDefaultEntities)
    {
        $this->enableDefaultEntities = $enableDefaultEntities;
        return $this;
    }
    /**
     * @return boolean
     */
    public function getEnableDefaultEntities()
    {
        return $this->enableDefaultEntities;
    }
}