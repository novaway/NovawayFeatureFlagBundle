<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Fixtures\App\TestBundle\Controller;

use Novaway\Bundle\FeatureFlagBundle\Annotation\Feature;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

#[Feature(name: 'foo', enabled: false)]
class AttributeClassDisabledController extends AbstractController
{
    public function __invoke()
    {
        return new Response('AttributeClassDisabledController::response');
    }
}
