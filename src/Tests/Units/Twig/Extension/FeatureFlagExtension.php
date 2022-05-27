<?php

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Units\Twig\Extension;

use atoum;

class FeatureFlagExtension extends atoum
{
    public function testIsFeatureEnabled()
    {
        $this
            ->given($manager = $this->createManagerMock())
            ->if($this->newTestedInstance($manager))
            ->and($function = $this->getFunction('isFeatureEnabled'))
            ->then
                ->variable($function)->isNotNull()
                ->boolean(call_user_func($function, 'foo'))->isTrue()
                ->boolean(call_user_func($function, 'bar'))->isFalse()
        ;
    }

    public function testIsFeatureDisabled()
    {
        $this
            ->given($manager = $this->createManagerMock())
            ->if($this->newTestedInstance($manager))
            ->and($function = $this->getFunction('isFeatureDisabled'))
            ->then
                ->variable($function)->isNotNull()
                ->boolean(call_user_func($function, 'foo'))->isFalse()
                ->boolean(call_user_func($function, 'bar'))->isTrue()
        ;
    }

    private function createManagerMock()
    {
        $manager = new \mock\Novaway\Bundle\FeatureFlagBundle\Storage\StorageInterface();
        $manager->getMockController()->all = function ($feature) {
            return ['foo'];
        };
        $manager->getMockController()->isEnabled = function ($feature) {
            return 'foo' === $feature;
        };
        $manager->getMockController()->isDisabled = function ($feature) {
            return 'foo' !== $feature;
        };

        return $manager;
    }

    private function getFunction($name)
    {
        foreach ($this->testedInstance->getFunctions() as $function) {
            if ($name === $function->getName()) {
                return $function->getCallable();
            }
        }

        return null;
    }
}
