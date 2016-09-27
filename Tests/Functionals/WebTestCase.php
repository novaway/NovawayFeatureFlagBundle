<?php

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Functionals;

use atoum\AtoumBundle\Test\Units\WebTestCase as BaseWebTestCase;
use mageekguy\atoum;
use Novaway\Bundle\FeatureFlagBundle\Tests\Fixtures\AppKernel;

abstract class WebTestCase extends BaseWebTestCase
{
    public function __construct(atoum\adapter $adapter = null, atoum\annotations\extractor $annotationExtractor = null, atoum\asserter\generator $asserterGenerator = null, atoum\test\assertion\manager $assertionManager = null, \closure $reflectionClassFactory = null)
    {
        parent::__construct($adapter, $annotationExtractor, $asserterGenerator, $assertionManager, $reflectionClassFactory);

        $this->setTestNamespace('Functionals');
    }

    protected function createKernel(array $options = array())
    {
        return new AppKernel('test', true);
    }
}
