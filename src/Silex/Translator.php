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

use Symfony\Component\Translation\Formatter\MessageFormatterInterface;
use Symfony\Component\Translation\Translator as BaseTranslator;

/**
 * Translator that gets the current locale from the Silex application.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class Translator extends BaseTranslator
{
    protected $app;

    public function __construct(Application $app, ?MessageFormatterInterface $formatter = null, ?string $cacheDir = null, bool $debug = false)
    {
        $this->app = $app;

        parent::__construct($this->app['locale'], $formatter, $cacheDir, $debug);
    }

    public function getLocale(): string
    {
        return $this->app['locale'];
    }

    public function setLocale(string $locale)
    {
        if (null === $locale) {
            return;
        }

        $this->app['locale'] = $locale;

        parent::setLocale($locale);
    }
}
