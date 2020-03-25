<?php

/**
 * @author     School Assistant Developers Team
 * @copyright  2018-2018 School Assistant
 * @license    Any usage is forbidden
 */

namespace App\Model\Config;

use Symfony\Component\Yaml\Yaml;

class YamlLoader
{
    private $baseDir;

    // ########################################

    public function __construct(string $baseDir)
    {
        $this->baseDir = $baseDir;
    }

    // ########################################

    public function load($resourceName)
    {
        return Yaml::parse(file_get_contents($this->baseDir . '/config/' . $resourceName));
    }

    // ########################################
}
