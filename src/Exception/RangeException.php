<?php

declare(strict_types=1);

namespace Fabiang\DoctrineDynamic\Exception;

use RangeException as BaseRangeException;

class RangeException extends BaseRangeException implements ExceptionInterface
{
}
