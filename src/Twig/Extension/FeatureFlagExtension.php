<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Twig\Extension;

use Novaway\Bundle\FeatureFlagBundle\Manager\FeatureManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FeatureFlagExtension extends AbstractExtension
{
    /** @var FeatureManager */
    private $storage;

    /**
     * Constructor
     */
    public function __construct(FeatureManager $storage)
    {
        $this->storage = $storage;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('isFeatureEnabled', [$this->storage, 'isEnabled']),
            new TwigFunction('isFeatureDisabled', [$this->storage, 'isDisabled']),
        ];
    }

    public function getName(): string
    {
        return 'feature_flag_extension';
    }
}
