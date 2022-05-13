<?php

declare(strict_types=1);

namespace Fabiang\DoctrineDynamic\Exception;

use OutOfBoundsException as BaseOutOfBoundsException;

class OutOfBoundsException extends BaseOutOfBoundsException implements ExceptionInterface
{
}
