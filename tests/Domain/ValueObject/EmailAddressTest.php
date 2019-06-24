<?php


namespace Antevenio\DddExample\Domain\ValueObject;

use Assert\LazyAssertionException;
use PHPUnit\Framework\TestCase;

class EmailAddressTest extends TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate($input, $expectedEmail, $expectedName)
    {
        $address = EmailAddress::create($input);
        $this->assertEquals($expectedEmail, $address->getEmail());
        $this->assertEquals($expectedName, $address->getName());
    }

    public function createDataProvider()
    {
        return [
            ['foo@antevenio.com', 'foo@antevenio.com', ''],
            [['foo@antevenio.com' => 'Foo'], 'foo@antevenio.com', 'Foo'],
            ['Foo <foo@antevenio.com>', 'foo@antevenio.com', 'Foo'],
        ];
    }

    /**
     * @dataProvider createModelValidationExceptionProvider
     */
    public function testCreateThrowsEmptyAddressException($input)
    {
        $this->expectException(LazyAssertionException::class);
        $address = EmailAddress::create($input);
    }

    public function createModelValidationExceptionProvider()
    {
        return [
            [''],
            ['foo'],
            ['foo @ antevenio .com'],
        ];
    }
}
