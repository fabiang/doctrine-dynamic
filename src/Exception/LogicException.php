<?php

declare(strict_types=1);

namespace Fabiang\DoctrineDynamic\Exception;

use LogicException as BaseLogicException;

class LogicException extends BaseLogicException implements ExceptionInterface
{
}
