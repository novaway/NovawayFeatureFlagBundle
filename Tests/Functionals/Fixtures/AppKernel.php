<?php

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Functionals\Fixtures;

use atoum;
use Novaway\Bundle\FeatureFlagBundle\Tests\Fixtures\AppKernel as TestedClass;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @namespace Functionals
 * @engine inline
 */
class AppKernel extends atoum
{
    private $kernel;

    public function setUp()
    {
        $this->kernel = new TestedClass('dev', true);
        if (file_exists($this->kernel->getBasePath())) {
            $fs = new Filesystem();
            $fs->remove($this->kernel->getBasePath());
        }

        $this->kernel->boot();
    }

    public function tearDown()
    {
        $fs = new Filesystem();
        $fs->remove($this->kernel->getBasePath());
    }

    public function testFeatureEnable()
    {
        $this
            ->if($this->kernel)
            ->then
                ->string($this->kernel->getContainer()->get('twig')->render('index.html.twig'))
                    ->contains('Foo is activate')
                    ->notContains('Bar is activate')
        ;
    }

    public function testFeatureDisable()
    {
        $this
            ->if($this->kernel)
            ->then
                ->string($this->kernel->getContainer()->get('twig')->render('index.html.twig'))
                    ->contains('Bar is disable')
                    ->notContains('Foo is disable')
        ;
    }
}
