<?php

namespace DorianNeto\SymfonyProfaneBundle\Tests;

use DorianNeto\SymfonyProfaneBundle\Validator\NotProfane;
use DorianNeto\SymfonyProfaneBundle\Validator\NotProfaneValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class ProfaneValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator()
    {
        return new NotProfaneValidator;
    }

    public function test_empty_is_valid(): void
    {
        $this->validator->validate('', new NotProfane());

        $this->assertNoViolation();
    }

    public function test_null_is_valid(): void
    {
        $this->validator->validate(null, new NotProfane());

        $this->assertNoViolation();
    }

    /**
     * @dataProvider getValidValues
     */
    public function test_valid_values(string $value): void
    {
        $this->validator->validate($value, new NotProfane());

        $this->assertNoViolation();
    }

    public function getValidValues(): array
    {
        return [
            ['Gostei bastante do artigo.'],
            ['Muito bom!'],
        ];
    }

    /**
     * @dataProvider getInvalidValues
     */
    public function test_invalid_values(string $value, string $expected): void
    {
        $constraint = new NotProfane();

        $this->validator->validate($value, $constraint);

        $this->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $expected)
            ->assertRaised();
    }

    public function getInvalidValues(): array
    {
        return [
            [
                'value' => 'Odiei a porra desse artigo.',
                'expected' => 'p****',
            ],
            [
                'value' => 'Isso ta um caralho!',
                'expected' => 'c******',
            ],
        ];
    }
}
