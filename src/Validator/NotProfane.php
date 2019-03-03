<?php

namespace DorianNeto\SymfonyProfaneBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotProfane extends Constraint
{
    public $message = 'The value "{{ value }}" is not a profane word.';
}
