<?php

/*
 * This file is part of the Silex framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Silex;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolver as BaseControllerResolver;

/**
 * Adds Application as a valid argument for controllers.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class ControllerResolver extends BaseControllerResolver
{
    protected Application $app;

    /**
     * Constructor.
     *
     * @param Application                   $app An Application instance
     * @param \Psr\Log\LoggerInterface|null $logger A LoggerInterface instance
     */
    public function __construct(Application $app, ?LoggerInterface $logger = null)
    {
        $this->app = $app;

        parent::__construct($logger);
    }
}
