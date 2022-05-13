<?php

declare(strict_types=1);

namespace Fabiang\DoctrineDynamic\Configuration\Exception;

use Fabiang\DoctrineDynamic\Exception\LengthException as BaseLengthException;

class LengthException extends BaseLengthException implements ExceptionInterface
{
}
