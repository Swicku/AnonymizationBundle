<?php

namespace Swicku\AnonymizationBundle\Tests\UnitTest;

use PHPUnit\Framework\TestCase;
use Swicku\AnonymizationBundle\Annotation\Anonymize;
use Swicku\AnonymizationBundle\Annotation\AttributeReader;

class MetaDataTest extends TestCase
{
    private $instanceOfClassWithAnonymizeAttribute;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

//        Anonymous class for testing purposes
        $this->instanceOfClassWithAnonymizeAttribute = new class {
            #[Anonymize(type: 'mask')]
            private string $name = "TestName";
        };
    }

    public function testGetAttributeValue(): void
    {
        $attributeReader = new AttributeReader();
        $reflectionClass = new \ReflectionClass($this->instanceOfClassWithAnonymizeAttribute);
        $propertiesArray = $attributeReader->getPropertiesWithAttribute($reflectionClass, Anonymize::class);
        self::assertArrayHasKey('name', $propertiesArray);
    }
}
