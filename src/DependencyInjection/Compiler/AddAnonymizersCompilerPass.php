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

namespace Swicku\AnonymizationBundle\DependencyInjection\Compiler;

use LogicException;
use Swicku\AnonymizationBundle\Anonymizer\AnonymizerCollection;
use Swicku\AnonymizationBundle\Anonymizer\Type\AnonymizerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Compiler pass to add the tagged anonymizers to the anonymizer manager's definition.
 */
class AddAnonymizersCompilerPass implements CompilerPassInterface
{
    /**
     * Gets all the anonymizers from the services tagged with 'superbrave_gdpr.anonymizer'
     * Then adds them to the AnonymizerCollection.
     *
     * @param ContainerBuilder $container The service container
     *
     * @return void
     *
     * @throws LogicException on invalid interface usage
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition(AnonymizerCollection::class) === false) {
            return;
        }

        $anonymizerManagerDefinition = $container->getDefinition(AnonymizerCollection::class);

        $anonymizers = $container->findTaggedServiceIds('swicku.anonymizer');

        foreach ($anonymizers as $anonymizer => $attributes) {
            $type = $attributes[0]['type'];

            $definition = $container->getDefinition($anonymizer);

            //validate class interface
            $class = $container->getParameterBag()->resolveValue($definition->getClass());
            if (is_subclass_of($class, AnonymizerInterface::class) === false) {
                throw new LogicException(
                    sprintf(
                        '%s should implement the %s interface when used as anonymizer.',
                        $class,
                        AnonymizerInterface::class
                    )
                );
            }

            $anonymizerManagerDefinition->addMethodCall('addAnonymizer', [$type, new Reference($anonymizer)]);
        }
    }
}
