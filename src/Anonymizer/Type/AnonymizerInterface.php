<?php

namespace Swicku\AnonymizationBundle\Anonymizer\Type;

interface AnonymizerInterface
{
    public function anonymize($propertyValue, array $options = []);
}
