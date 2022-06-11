<?php

namespace Swicku\AnonymizationBundle\Anonymizer\Type;

class MaskAnonymizer implements AnonymizerInterface
{
    public const DEFAULT_MAX_MASK_LENGTH = 15;

    public function __construct()
    {
    }

    public function anonymize($propertyValue, array $options = [])
    {
        if (!$propertyValue) {
            return null;
        }

        try {
            $propertyValue = (string)$propertyValue;
        } catch (\Exception $e) {
            return str_repeat($maskChar, rand(5, self::DEFAULT_MAX_MASK_LENGTH));
        }

        $maxLength = $options['maxLength'] ?? self::DEFAULT_MAX_MASK_LENGTH;
        $maskChar = $options['maskChar'] ?? '*';
        $unique = $options['unique'] ?? true;
        $randomChars = $options['randomChars'] ?? 5;

        return $this->maskProperty($propertyValue, $maxLength, $maskChar, $unique, $randomChars);
    }

    private function maskProperty($propertyValue, ?int $maxLength, string $maskChar, bool $unique, int $randomChars): ?string
    {
        $propertyValue .= $this->randomCharacters(random_int(1, $randomChars));
        $propertyLength = mb_strlen($propertyValue);

        if ($propertyLength > $maxLength) {
            $propertyLength -= ($propertyLength - $maxLength);
        }

        $mask = str_repeat($maskChar, $propertyLength);

        if ($unique) {
            $randomStringLength = ceil($propertyLength/2);

            $mask = substr($mask,0, $propertyLength - $randomStringLength);
            $mask .= $this->randomCharacters($randomStringLength);
        }

        return $mask;
    }

    private function randomCharacters($stringLength): string
    {
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($str_result), 0, $stringLength);
    }
}
