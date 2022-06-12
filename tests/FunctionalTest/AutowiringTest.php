<?php

use Swicku\AnonymizationBundle\AnonymizationBundle;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Swicku\AnonymizationBundle\Anonymizer\Anonymizer;
use PHPUnit\Framework\TestCase;

class AutowiringTest extends TestCase
{
    public function testAnonymizerInstantiating()
    {
        $kernel = new SwickuAnonymizationTestingKernel('test', true);
        $kernel->boot();
        $container = $kernel->getContainer();

        $anonymizer = $container->get('swicku.global_anonymizer');
        $this->assertInstanceOf(Anonymizer::class, $anonymizer);
    }
}

class SwickuAnonymizationTestingKernel extends Kernel
{
    public function registerBundles(): iterable
    {
        return [
            new AnonymizationBundle()
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
    }
}
