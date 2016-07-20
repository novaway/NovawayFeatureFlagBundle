<?php

namespace Novaway\Bundle\FeatureFlagBundle\Twig\Extension;

use Novaway\Bundle\FeatureFlagBundle\Manager\FeatureFlagManager;

class FeatureFlagExtension extends \Twig_Extension
{
    /** @var FeatureFlagManager */
    private $featureFlagManager;

    /**
     * Constructor
     *
     * @param FeatureFlagManager $flagManager
     */
    public function __construct(FeatureFlagManager $flagManager)
    {
        $this->featureFlagManager = $flagManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('isFeatureEnabled', [$this->featureFlagManager, 'isEnabled']),
            new \Twig_SimpleFunction('isFeatureDisabled', [$this->featureFlagManager, 'isDisabled']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'feature_flag_extension';
    }
}
