<?php

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Units\Storage;

use atoum;

class ArrayStorage extends atoum
{
    public function testIsEnabled()
    {
        $this
            ->if($this->newTestedInstance())
            ->then
                ->boolean($this->testedInstance->isEnabled('foo'))->isFalse()

            ->if($this->newTestedInstance(['foo' => false, 'bar' => true]))
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

            ->if($this->newTestedInstance(['foo' => false, 'bar' => true]))
            ->then
                ->boolean($this->testedInstance->isDisabled('foo'))->isTrue()
                ->boolean($this->testedInstance->isDisabled('bar'))->isFalse()
                ->boolean($this->testedInstance->isDisabled('unknow'))->isTrue()
        ;
    }
}
