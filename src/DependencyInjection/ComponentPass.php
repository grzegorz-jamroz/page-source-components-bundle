<?php

declare(strict_types=1);

namespace Ifrost\Bundle\PageSourceComponentsBundle\DependencyInjection;

use Ifrost\PageSourceComponents\ComponentCollection;
use Ifrost\PageSourceComponents\ComponentInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

class ComponentPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(ComponentCollection::class);
        $taggedServices = $container->findTaggedServiceIds('app.page-source-component');
        $typenames = [];

        foreach ($taggedServices as $id => $tags) {
            if (!is_subclass_of($id, ComponentInterface::class)) {
                throw new RuntimeException(sprintf('Invalid component "%s": class must implement %s.', $id, ComponentInterface::class));
            }

            $definition->addMethodCall('set', [$id::getTypename(), $id]);
            $typename = $id::getTypename();
            $typenames[] = $typename;
        }

        $this->validateUniqueTypenames($typenames);
    }

    /**
     * @param array<int, string> $typenames
     */
    private function validateUniqueTypenames(array $typenames): bool
    {
        $notUniqueTypenames = array_diff_key($typenames, array_unique($typenames));

        if (count($notUniqueTypenames) > 0) {
            throw new RuntimeException(sprintf('%s::getTypename has to return unique typename. Non unique typenames: %s.', ComponentInterface::class, implode(', ', $notUniqueTypenames)));
        }

        return true;
    }
}
