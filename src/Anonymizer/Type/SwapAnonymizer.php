<?php

namespace Swicku\AnonymizationBundle\Anonymizer\Type;

use Symfony\Component\Routing\Exception\InvalidParameterException;

class SwapAnonymizer implements AnonymizerInterface
{
    public function anonymize($propertyValue, array $options = [])
    {
        if (!isset($options['value'])) {
            throw new InvalidParameterException('Swap value must be provided');
        }

        return $options['value'];
    }
}
