<?php

namespace Novaway\Bundle\FeatureFlagBundle\Twig\Extension;

use Novaway\Bundle\FeatureFlagBundle\Storage\StorageInterface;

class FeatureFlagExtension extends \Twig_Extension
{
    /** @var StorageInterface */
    private $storage;

    /**
     * Constructor
     *
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('isFeatureEnabled', [$this->storage, 'isEnabled']),
            new \Twig_SimpleFunction('isFeatureDisabled', [$this->storage, 'isDisabled']),
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
