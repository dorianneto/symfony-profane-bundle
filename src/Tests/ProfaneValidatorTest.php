<?php

namespace DorianNeto\SymfonyProfaneBundle\Tests;

use DorianNeto\SymfonyProfaneBundle\Validator\NotProfane;
use DorianNeto\SymfonyProfaneBundle\Validator\NotProfaneValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class ProfaneValidatorTest extends ConstraintValidatorTestCase
{
    protected function tearDown()
    {
        \Locale::setDefault('en');
    }

    protected function createValidator()
    {
        return new NotProfaneValidator;
    }

    public function testIsValidWithEmptyValue(): void
    {
        $this->validator->validate('', new NotProfane());

        $this->assertNoViolation();
    }

    public function testIsValidWithNullValue(): void
    {
        $this->validator->validate(null, new NotProfane());

        $this->assertNoViolation();
    }

    /**
     * @dataProvider profaneValuesProvider
     */
    public function testIsValidWithProfaneWords(string $value): void
    {
        $this->validator->validate($value, new NotProfane());

        $this->assertNoViolation();
    }

    public function profaneValuesProvider(): array
    {
        return [
            ['I\'ve been studying a lot.'],
            ['This is a good Symfony\'s bundle!'],
            ['Work hard!'],
        ];
    }

    /**
     * @dataProvider notProfaneValuesProvider
     */
    public function testIsInvalidWithNotProfaneWords(string $value, string $expected): void
    {
        $constraint = new NotProfane();

        $this->validator->validate($value, $constraint);

        $this->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $expected)
            ->assertRaised();
    }

    public function notProfaneValuesProvider(): array
    {
        return [
            [
                'value' => 'This shit doesn\'t work!',
                'expected' => 's***',
            ],
            [
                'value' => 'I hate this computer, fuck!',
                'expected' => 'f***',
            ],
            [
                'value' => 'Damn, dude! What are you doing?!',
                'expected' => 'D***',
            ],
        ];
    }

    /**
     * @dataProvider notProfaneValuesInSeveralLanguagesProvider
     */
    public function testIsInvalidWithNotProfaneWordsInSeveralLanguages(
        string $value,
        string $expected,
        string $language
    ): void {
        \Locale::setDefault($language);

        $constraint = new NotProfane();

        $this->validator->validate($value, $constraint);

        $this->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $expected)
            ->assertRaised();
    }

    public function notProfaneValuesInSeveralLanguagesProvider(): array
    {
        return [
            [
                'value' => 'Que porra foi essa?',
                'expected' => 'p****',
                'language' => 'pt_BR',
            ],
            [
                'value' => 'Damn it! I missed my bus!',
                'expected' => 'D***',
                'language' => 'en',
            ],
        ];
    }
}
