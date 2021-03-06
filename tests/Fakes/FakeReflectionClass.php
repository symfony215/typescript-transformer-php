<?php

namespace Spatie\TypeScriptTransformer\Tests\Fakes;

use ReflectionClass;

class FakeReflectionClass extends ReflectionClass
{
    use FakedReflection;

    private string $withNamespace = '';

    public function __construct()
    {
        parent::__construct(new class {
        });
    }

    public function withNamespace(string $namespace): self
    {
        $this->withNamespace = $namespace;

        return $this;
    }

    public function withoutNamespace(): self
    {
        $this->withNamespace = '';

        return $this;
    }

    public function getNamespaceName(): string
    {
        return $this->withNamespace ?: parent::getNamespaceName();
    }

    public function getName(): string
    {
        $name = $this->entityName ?? parent::getShortName();

        return empty($this->getNamespaceName())
            ? $name
            : "{$this->getNamespaceName()}\\{$name}";
    }
}
