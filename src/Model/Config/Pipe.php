<?php

/**
 * @author     School Assistant Developers Team
 * @copyright  2018-2018 School Assistant
 * @license    Any usage is forbidden
 */

namespace App\Model\Config;

class Pipe
{
    /** @var array */
    private $data;

    // ########################################

    public function __construct(YamlLoader $loader)
    {
        $this->data = $loader->load('pipe.yaml');
    }

    // ########################################

    public function getApiKey(): string
    {
        return (string)$this->data['apikey'];
    }

    public function getHost(): string
    {
        return (string)$this->data['host'];
    }


    // ########################################
}
