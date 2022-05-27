<?php

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Functionals\Fixtures\TestBundle;

use Novaway\Bundle\FeatureFlagBundle\Model\Feature;
use Novaway\Bundle\FeatureFlagBundle\Tests\Functionals\WebTestCase;

class TestBundle extends WebTestCase
{
    public function testDefaultFeatureManagerInstance()
    {
        $this
            ->if($manager = $this->createDefaultFeatureManager())
            ->then
                ->object($manager)
                    ->isInstanceOf('Novaway\Bundle\FeatureFlagBundle\Storage\ArrayStorage')
        ;
    }

    public function testIsEnabled()
    {
        $this
            ->if($manager = $this->createDefaultFeatureManager())
            ->then
                ->boolean($manager->isEnabled('override'))
                    ->isFalse()
                ->boolean($manager->isEnabled('foo'))
                    ->isTrue()
                ->boolean($manager->isEnabled('bar'))
                    ->isFalse()
        ;
    }

    public function testIsDisabled()
    {
        $this
            ->if($manager = $this->createDefaultFeatureManager())
            ->then
                ->boolean($manager->isDisabled('override'))
                    ->isTrue()
                ->boolean($manager->isDisabled('foo'))
                    ->isFalse()
                ->boolean($manager->isDisabled('bar'))
                    ->isTrue()
        ;
    }

    public function testChecked()
    {
        $this
            ->if($manager = $this->createDefaultFeatureManager())
            ->then
               ->boolean($manager->check('override'))
                    ->isFalse()
                ->boolean($manager->check('foo'))
                    ->isTrue()
                ->boolean($manager->check('bar'))
                    ->isFalse()
        ;
    }

    public function testAccessAllFeatures()
    {
        $this
            ->if($manager = $this->createDefaultFeatureManager())
            ->then
                ->array($manager->all())
                    ->hasSize(3)
                    ->object['override']
                        ->isInstanceOf('Novaway\Bundle\FeatureFlagBundle\Model\Feature')
                        ->isEqualTo(new Feature('override', false))
                    ->object['foo']
                        ->isInstanceOf('Novaway\Bundle\FeatureFlagBundle\Model\Feature')
                        ->isEqualTo(new Feature('foo', true))
                    ->object['bar']
                        ->isInstanceOf('Novaway\Bundle\FeatureFlagBundle\Model\Feature')
                        ->isEqualTo(new Feature('bar', false, 'Bar feature description'))
        ;
    }

    /**
     * @return \Novaway\Bundle\FeatureFlagBundle\Storage\StorageInterface
     */
    private function createDefaultFeatureManager()
    {
        $client = $this->createClient();

        return $client->getContainer()->get('novaway_feature_flag.manager.feature');
    }
}
