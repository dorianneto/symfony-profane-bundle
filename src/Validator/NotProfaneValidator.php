<?php

namespace DorianNeto\SymfonyProfaneBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class NotProfaneValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        /* @var $constraint App\Validator\NotProfane */

        if (!$constraint instanceof NotProfane) {
            throw new UnexpectedTypeException($constraint, NotProfane::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (preg_match('/('.implode($this->loadDictionary(), '|').')/i', $value, $profaneWord) > 0) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->maskProfaneWord($profaneWord[0]))
                ->addViolation();
        }
    }

    private function maskProfaneWord(string $word): string
    {
        $wordLength = strlen($word);

        return substr($word, 0, 1) . str_repeat('*', $wordLength-1);
    }

    private function loadDictionary(): array
    {
        return include __DIR__.'/../Resources/dict/pt-br.php';
    }
}
