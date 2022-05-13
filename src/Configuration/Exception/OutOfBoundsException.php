<?php

declare(strict_types=1);

namespace Fabiang\DoctrineDynamic\Configuration\Exception;

use Fabiang\DoctrineDynamic\Exception\OutOfBoundsException as BaseOutOfBoundsException;

class OutOfBoundsException extends BaseOutOfBoundsException implements ExceptionInterface
{
}
