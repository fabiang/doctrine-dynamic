<?php

declare(strict_types=1);

namespace Fabiang\DoctrineDynamic\Exception;

use LengthException as BaseLengthException;

class LengthException extends BaseLengthException implements ExceptionInterface
{
}
