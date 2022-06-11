<?php
/**
 * This file is part of the GDPR bundle.
 *
 * @category  Bundle
 *
 * @author    SuperBrave <info@superbrave.nl>
 * @copyright 2018 SuperBrave <info@superbrave.nl>
 * @license   https://github.com/superbrave/gdpr-bundle/blob/master/LICENSE MIT
 *
 * @see       https://www.superbrave.nl/
 */

namespace Swicku\AnonymizationBundle\Anonymizer\Type;

use Swicku\AnonymizationBundle\Anonymizer\Anonymizer;

class ObjectAnonymizer implements AnonymizerInterface
{
    private $anonymizer;

    public function __construct(Anonymizer $anonymizer)
    {
        $this->anonymizer = $anonymizer;
    }

    public function anonymize($propertyValue, array $options = [])
    {
        if (!is_object($propertyValue)) {
            return null;
        }

        $this->anonymizer->anonymize($propertyValue);

        return $propertyValue;
    }
}
