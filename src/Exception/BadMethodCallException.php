<?php

declare(strict_types=1);

namespace Fabiang\DoctrineDynamic\Exception;

use BadMethodCallException as BaseBadMethodCallException;

class BadMethodCallException extends BaseBadMethodCallException implements ExceptionInterface
{
}
