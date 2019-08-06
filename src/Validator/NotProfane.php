<?php

namespace DorianNeto\SymfonyProfaneBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotProfane extends Constraint
{
    public $message = 'symfony_profane.bundle.message';
}
