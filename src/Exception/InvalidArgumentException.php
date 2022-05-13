<?php

declare(strict_types=1);

namespace Fabiang\DoctrineDynamic\Exception;

use InvalidArgumentException as BaseInvalidArgumentException;

class InvalidArgumentException extends BaseInvalidArgumentException implements ExceptionInterface
{
}
