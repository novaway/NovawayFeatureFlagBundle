<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Attribute;

abstract class FeatureAttribute
{
    /**
     * @param class-string<\Throwable>|null $exceptionClass
     */
    public function __construct(
        public readonly string $name,
        public readonly ?string $exceptionClass = null,
    ) {
        if ($this->exceptionClass) {
            if (!class_exists($this->exceptionClass)) {
                throw new \InvalidArgumentException("Class '{$this->exceptionClass}' does not exist.");
            }

            if (!is_a($this->exceptionClass, \Throwable::class, true)) {
                throw new \InvalidArgumentException("Class '{$this->exceptionClass}' is not a valid exception class.");
            }
        }
    }

    /**
     * @return array{feature: string, enabled: bool, exceptionClass: class-string<\Throwable>|null}
     */
    public function toArray(): array
    {
        return [
            'feature' => $this->name,
            'enabled' => $this->shouldBeEnabled(),
            'exceptionClass' => $this->exceptionClass,
        ];
    }

    abstract protected function shouldBeEnabled(): bool;
}
