<?php

declare(strict_types=1);

namespace Fabiang\DoctrineDynamic\Exception;

use RuntimeException as BaseRuntimeException;

class RuntimeException extends BaseRuntimeException implements ExceptionInterface
{
}
