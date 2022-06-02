<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Functional;

final class TwigTest extends WebTestCase
{
    public function testTwigRenderWithFeatureEnable(): void
    {
        $content = $this->twigRender('index.html.twig');

        static::assertStringContainsString('Foo is activated', $content);
        static::assertStringNotContainsString('Bar is activated', $content);
    }

    public function testTwigRenderWithFeatureDisabled(): void
    {
        $content = $this->twigRender('index.html.twig');

        static::assertStringContainsString('Bar is disabled', $content);
        static::assertStringNotContainsString('Foo is disabled', $content);
    }

    private function twigRender(string $template): string
    {
        return static::getContainer()->get('twig')->render($template);
    }
}
