<?php

declare(strict_types=1);

namespace App\Model\Curl\Exception;

class UnableProcess extends \Exception
{
    /** @var array */
    private $additionalData;

    // ########################################

    public function __construct(string $message, array $additionalData = [], int $code = 0, \Throwable $previous = null)
    {
        $this->additionalData = $additionalData;

        parent::__construct($message, $code, $previous);
    }

    // ########################################

    public function getAdditionalData(): array
    {
        return $this->additionalData;
    }

    // ########################################
}
