<?php

namespace Swicku\AnonymizationBundle\Annotation;

use Attribute;
use Doctrine\ORM\Mapping\Annotation;
use Doctrine\ORM\Mapping\Driver\RepeatableAttributeCollection;
use LogicException;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

class AttributeReader
{
    public function __construct()
    {
    }


    public function getPropertiesWithAttribute(ReflectionClass $class, $annotationName): array
    {
        $annotatedProperties = [];

        $properties = $class->getProperties();
        foreach ($properties as $property) {
            $annotation = $this->getPropertyAnnotation($property, $annotationName);
            if ($annotation instanceof $annotationName) {
                $annotatedProperties[$property->getName()] = $annotation;
            }
        }

        $parentClass = $class->getParentClass();
        if ($parentClass instanceof ReflectionClass) {
            $annotatedProperties = array_merge(
                $annotatedProperties,
                $this->getPropertiesWithAttribute($parentClass, $annotationName)
            );
        }

        return $annotatedProperties;
    }

    private array $isRepeatableAttribute = [];

    public function getClassAnnotations(ReflectionClass $class): array
    {
        return $this->convertToAttributeInstances($class->getAttributes());
    }

    public function getMethodAnnotations(ReflectionMethod $method): array
    {
        return $this->convertToAttributeInstances($method->getAttributes());
    }

    public function getPropertyAnnotations(ReflectionProperty $property): array
    {
        return $this->convertToAttributeInstances($property->getAttributes());
    }

    public function getPropertyAnnotation(ReflectionProperty $property, $annotationName)
    {
        if ($this->isRepeatable($annotationName)) {
            throw new LogicException(sprintf(
                'The attribute "%s" is repeatable. Call getPropertyAnnotationCollection() instead.',
                $annotationName
            ));
        }

        return $this->getPropertyAnnotations($property)[$annotationName]
            ?? ($this->isRepeatable($annotationName) ? new RepeatableAttributeCollection() : null);
    }

    public function getPropertyAnnotationCollection(
        ReflectionProperty $property,
        string $annotationName
    ): RepeatableAttributeCollection
    {
        if (!$this->isRepeatable($annotationName)) {
            throw new LogicException(sprintf(
                'The attribute "%s" is not repeatable. Call getPropertyAnnotation() instead.',
                $annotationName
            ));
        }

        return $this->getPropertyAnnotations($property)[$annotationName] ?? new RepeatableAttributeCollection();
    }

    private function convertToAttributeInstances(array $attributes): array
    {
        $instances = [];

        foreach ($attributes as $attribute) {
            $attributeName = $attribute->getName();
            assert(is_string($attributeName));
            if ($attributeName !== Anonymize::class) {
                continue;
            }

            $instance = $attribute->newInstance();
            assert($instance instanceof Anonymize);
            if ($this->isRepeatable($attributeName)) {
                if (!isset($instances[$attributeName])) {
                    $instances[$attributeName] = new RepeatableAttributeCollection();
                }

                $collection = $instances[$attributeName];
                assert($collection instanceof RepeatableAttributeCollection);
                $collection[] = $instance;
            } else {
                $instances[$attributeName] = $instance;
            }
        }
        return $instances;
    }

    private function isRepeatable(string $attributeClassName): bool
    {
        if (isset($this->isRepeatableAttribute[$attributeClassName])) {
            return $this->isRepeatableAttribute[$attributeClassName];
        }

        $reflectionClass = new ReflectionClass($attributeClassName);
        $attribute = $reflectionClass->getAttributes()[0]->newInstance();

        return $this->isRepeatableAttribute[$attributeClassName] = ($attribute->flags & Attribute::IS_REPEATABLE) > 0;
    }

}
