<?php

namespace Lyhty\Macros\Collection;

use Closure;
use Illuminate\Support\Str;

/**
 * @mixin \Illuminate\Support\Collection
 */
class PickMacro
{
    public const PRESERVE_KEYS_FULL = PICK_WITH_FULL_KEYS;

    public const PRESERVE_KEYS_PARTIAL = PICK_WITH_PARTIAL_KEYS;

    public const PRESERVE_KEYS_NONE = PICK_WITHOUT_KEYS;

    public function __invoke(): Closure
    {
        $macro = static::class;

        return function (array $attrs, int $preserveKeys = PICK_WITHOUT_KEYS) use ($macro) {
            $final = array_pad([], $this->count(), []);

            foreach ($attrs as $attrIndex => $attribute) {
                foreach ($this->pluck($attribute) as $index => $value) {
                    $finalKey = $macro::resolveKey($index, $attrIndex, $attribute, $preserveKeys);
                    data_set($final, $finalKey, $value);
                }
            }

            return new static($final);
        };
    }

    public static function resolveKey($itemIndex, $attributeIndex, $attribute, int $preserveKeys)
    {
        return "$itemIndex.".match ($preserveKeys) {
            PICK_WITHOUT_KEYS => $attributeIndex,
            PICK_WITH_PARTIAL_KEYS => Str::afterLast($attribute, '.'),
            PICK_WITH_FULL_KEYS => $attribute,
            default => $attribute,
        };
    }
}
