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

use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Defaults exception handler.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class ExceptionHandler implements EventSubscriberInterface
{
    protected $debug;
    protected $enabled;

    public function __construct($debug)
    {
        $this->debug = $debug;
        $this->enabled = true;
    }

    /**
     * @deprecated since 1.3, to be removed in 2.0
     */
    public function disable()
    {
        $this->enabled = false;
    }

    public function onSilexError(ExceptionEvent $event)
    {
        if (!$this->enabled) {
            return;
        }
        $throwable = $event->getThrowable();
        if ($throwable instanceof \Exception) {
            $exception = $throwable;
        } else {
            $exception = new \Exception($throwable->getMessage(), (int)$throwable->getCode(), $throwable);
        }

        $handler = new HtmlErrorRenderer($this->debug);

        if (!$exception instanceof FlattenException) {
            $exception = FlattenException::create($exception);
        }

        $response = (new Response($handler->getBody($exception), $exception->getStatusCode(), $exception->getHeaders()))
            ->setCharset(ini_get('default_charset'));
        $event->setResponse($response);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(KernelEvents::EXCEPTION => array('onSilexError', -255));
    }
}
