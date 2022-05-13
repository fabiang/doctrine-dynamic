<?php

declare(strict_types=1);

namespace Fabiang\DoctrineDynamic\Exception;

use UnexpectedValueException as BaseUnexpectedValueException;

class UnexpectedValueException extends BaseUnexpectedValueException implements ExceptionInterface
{
}
