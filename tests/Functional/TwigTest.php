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

        $this->assertStringContainsString('Foo is activated', $content);
        $this->assertStringNotContainsString('Bar is activated', $content);
    }

    public function testTwigRenderWithFeatureDisabled(): void
    {
        $content = $this->twigRender('index.html.twig');

        $this->assertStringContainsString('Bar is disabled', $content);
        $this->assertStringNotContainsString('Foo is disabled', $content);
    }

    public function testTwigRenderWithNonExistentFeatureIsConsideredAsDisabled(): void
    {
        $content = $this->twigRender('index.html.twig');

        $this->assertStringContainsString('Non existent feature is considered as disabled', $content);
    }

    private function twigRender(string $template): string
    {
        return $this->getContainer()->get('twig')->render($template);
    }
}
