<?php

namespace Swicku\AnonymizationBundle\Anonymizer;

use InvalidArgumentException;
use Swicku\AnonymizationBundle\Annotation\Anonymize;
use ReflectionClass;
use Swicku\AnonymizationBundle\Annotation\AttributeReader;

class Anonymizer
{
    public function __construct(private AttributeReader $annotationReader,
        private PropertyAnonymizer $propertyAnonymizer)
    {

    }

    public function anonymize($object)
    {
        if (!is_object($object)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid argument given "%s" should be of type object.',
                gettype($object)
            ));
        }

        $reflectionClass = new ReflectionClass($object);
        $annotations = $this->annotationReader->getPropertiesWithAttribute($reflectionClass, Anonymize::class);

        foreach ($annotations as $property => $annotation) {
            $this->propertyAnonymizer->anonymizeField($object, $property, $annotation);
        }
    }
}
