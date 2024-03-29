<?php

declare(strict_types=1);

namespace Fabiang\DoctrineDynamic\Configuration\Exception;

use Fabiang\DoctrineDynamic\Exception\UnexpectedValueException as BaseUnexpectedValueException;

class UnexpectedValueException extends BaseUnexpectedValueException implements ExceptionInterface
{
}
