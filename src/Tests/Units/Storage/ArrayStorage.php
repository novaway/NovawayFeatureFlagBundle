<?php

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Units\Storage;

use atoum;
use Novaway\Bundle\FeatureFlagBundle\Model\Feature;

class ArrayStorage extends atoum
{
    public function testAll()
    {
        $this
            ->if($this->newTestedInstance())
            ->then
                ->array($this->testedInstance->all())
                    ->isEmpty()

            ->if($this->newTestedInstance([
                'foo' => ['enabled' => false],
                'bar' => ['enabled' => true, 'description' => 'Feature bar description'],
            ]))
            ->then
                ->given($features = $this->testedInstance->all())
                ->array($features)
                    ->object['foo']->isEqualTo(new Feature('foo', false))
                    ->object['bar']->isEqualTo(new Feature('bar', true, 'Feature bar description'))
        ;
    }

    public function testCheck()
    {
        $this
            ->if($this->newTestedInstance())
            ->then
                ->boolean($this->testedInstance->check('foo'))->isFalse()

            ->if($this->newTestedInstance([
                'foo' => ['enabled' => false],
                'bar' => ['enabled' => true, 'description' => 'Feature bar description'],
            ]))
            ->then
                ->boolean($this->testedInstance->check('foo'))->isFalse()
                ->boolean($this->testedInstance->check('bar'))->isTrue()
                ->boolean($this->testedInstance->check('unknow'))->isFalse()
        ;
    }

    public function testIsEnabled()
    {
        $this
            ->if($this->newTestedInstance())
            ->then
                ->boolean($this->testedInstance->isEnabled('foo'))->isFalse()

            ->if($this->newTestedInstance([
                'foo' => ['enabled' => false],
                'bar' => ['enabled' => true, 'description' => 'Feature bar description'],
            ]))
            ->then
                ->boolean($this->testedInstance->isEnabled('foo'))->isFalse()
                ->boolean($this->testedInstance->isEnabled('bar'))->isTrue()
                ->boolean($this->testedInstance->isEnabled('unknow'))->isFalse()
        ;
    }

    public function testIsDisabled()
    {
        $this
            ->if($this->newTestedInstance())
            ->then
                ->boolean($this->testedInstance->isDisabled('foo'))->isTrue()

            ->if($this->newTestedInstance([
                'foo' => ['enabled' => false],
                'bar' => ['enabled' => true, 'description' => 'Feature bar description'],
            ]))
            ->then
                ->boolean($this->testedInstance->isDisabled('foo'))->isTrue()
                ->boolean($this->testedInstance->isDisabled('bar'))->isFalse()
                ->boolean($this->testedInstance->isDisabled('unknow'))->isTrue()
        ;
    }
}
