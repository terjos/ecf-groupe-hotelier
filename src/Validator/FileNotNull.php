<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_CLASS)]
class FileNotNull extends Constraint
{
    public $fieldName;
    public $fieldFile;
    public $message = 'Veuillez sélectionner un fichier.';

    public function __construct($fieldName, $fieldFile, $message)
    {
        parent::__construct();
        $this->fieldName = $fieldName;
        $this->fieldFile = $fieldFile;
        $this->message = $message ?? $this->message;
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
