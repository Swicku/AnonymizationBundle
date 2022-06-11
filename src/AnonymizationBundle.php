<?php

namespace Swicku\AnonymizationBundle;

use Swicku\AnonymizationBundle\DependencyInjection\Compiler\AddAnonymizersCompilerPass;
use Swicku\AnonymizationBundle\DependencyInjection\AnonymizationExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AnonymizationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AddAnonymizersCompilerPass());
    }

    /**
     * Overridden to allow for the custom extension alias.
     */
    public function getContainerExtension(): ?ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new AnonymizationExtension();
        }
        return $this->extension;
    }
}
