<?php

namespace Aperophp\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Date;

class FutureDate extends Date
{
    public $message = 'The date must be in the future';
}
