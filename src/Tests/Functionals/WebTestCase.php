<?php

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Functionals;

use atoum\atoum;
use Novaway\Bundle\FeatureFlagBundle\Tests\Fixtures\AppKernel;

abstract class WebTestCase extends atoum\test
{
    private $kernel;

    public function __construct(atoum\adapter $adapter = null, atoum\annotations\extractor $annotationExtractor = null, atoum\asserter\generator $asserterGenerator = null, atoum\test\assertion\manager $assertionManager = null, \closure $reflectionClassFactory = null)
    {
        parent::__construct($adapter, $annotationExtractor, $asserterGenerator, $assertionManager, $reflectionClassFactory);

        $this->setTestNamespace('Functionals');
    }

    public function createClient(array $options = array(), array $server = array(), array $cookies = array())
    {
        if (null !== $this->kernel && $this->kernelReset) {
            $this->kernel->shutdown();
            $this->kernel->boot();
        }

        if (null === $this->kernel) {
            $this->kernel = $this->createKernel($options);
            $this->kernel->boot();
        }

        $client = $this->kernel->getContainer()->get('test.client');
        $client->setServerParameters($server);

        foreach ($cookies as $cookie) {
            $client->getCookieJar()->set($cookie);
        }

        return $client;
    }

    protected function createKernel(array $options = array())
    {
        return new AppKernel('test', true);
    }
}
