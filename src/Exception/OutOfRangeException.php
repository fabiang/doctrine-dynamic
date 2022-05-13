<?php

declare(strict_types=1);

namespace Fabiang\DoctrineDynamic\Exception;

use OutOfRangeException as BaseOutOfRangeException;

class OutOfRangeException extends BaseOutOfRangeException implements ExceptionInterface
{
}
