<?php

namespace Spatie\TypescriptTransformer\ClassPropertyProcessors;

use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\AbstractList;
use phpDocumentor\Reflection\Types\Mixed_;
use phpDocumentor\Reflection\Types\Null_;
use ReflectionProperty;
use Spatie\TypescriptTransformer\Support\TypescriptType;

class ApplyNeverClassPropertyProcessor implements ClassPropertyProcessor
{
    use ProcessesClassProperties;

    public function process(Type $type, ReflectionProperty $reflection): ?Type
    {
        if ($this->shouldReplaceType($type)) {
            return new TypescriptType('never');
        }

        return $this->walk($type, function (Type $type) {
            if (! $type instanceof AbstractList) {
                return $type;
            }

            return $this->shouldReplaceType($type->getValueType())
                ? $this->updateListType($type, new TypescriptType('never'))
                : $type;
        });
    }

    private function shouldReplaceType(Type $type): bool
    {
        return $type instanceof Null_ || $type instanceof Mixed_;
    }
}
