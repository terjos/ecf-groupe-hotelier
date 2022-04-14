<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_CLASS)]
class UniqueReservation extends Constraint
{
    public $message = 'Cette suite est déjà réserver du {{ valueStart }} au {{ valueEnd }}.';

    public function __construct($message = null)
    {
        parent::__construct();
        $this->message = $message ?? $this->message;
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
