<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class FileNotNullValidator extends ConstraintValidator
{
    public function validate($picture, Constraint $constraint)
    {
        $getFieldName = 'get' . ucfirst($constraint->fieldName);
        $getFieldFile = 'get' . ucfirst($constraint->fieldFile);

        if ($picture->{$getFieldName}() === null && $picture->{$getFieldFile}() === null) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
