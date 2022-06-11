<?php

namespace Swicku\AnonymizationBundle\Annotation;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_CLASS)]
class Anonymize
{

    public string $type;
    public array $options;

    public function __construct(string $type, array $options = [])
    {
        $this->type = $type;
        $this->options = $options;
    }
}
