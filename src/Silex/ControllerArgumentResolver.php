<?php

namespace Silex;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadataFactory;

class ControllerArgumentResolver implements ArgumentResolverInterface
{
    protected Application $app;

    protected ArgumentResolver $symfonyResolver;

    public function __construct(Application $app, ?LoggerInterface $logger = null)
    {
        $this->app             = $app;
        $this->symfonyResolver = new ArgumentResolver();
    }

    public function getArguments(Request $request, callable $controller, ?\ReflectionFunctionAbstract $reflector = null): array
    {
        $argumentMetadataFactory = new ArgumentMetadataFactory();
        foreach ($argumentMetadataFactory->createArgumentMetadata($controller, $reflector) as $metadata) {
            if (is_a($this->app, $metadata->getType())) {
                $request->attributes->set($metadata->getName(), $this->app);

                break;
            }
        }

        return $this->symfonyResolver->getArguments($request, $controller);
    }
}
