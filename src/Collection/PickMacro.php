<?php

namespace Lyhty\Macros\Collection;

use Closure;
use Illuminate\Support\Str;

/**
 * @mixin \Illuminate\Support\Collection
 */
class PickMacro
{
    public const PRESERVE_KEYS_FULL = 2;

    public const PRESERVE_KEYS_PARTIAL = 1;

    public const PRESERVE_KEYS_NONE = 0;

    public function __invoke(): Closure
    {
        $macro = static::class;

        return function (
            array $attrs,
            int $preserveKeys = \Lyhty\Macros\Collection\PickMacro::PRESERVE_KEYS_NONE
        ) use ($macro) {
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
        switch ($preserveKeys) {
            case static::PRESERVE_KEYS_NONE:
                $last = $attributeIndex;
                break;
            case static::PRESERVE_KEYS_PARTIAL:
                $last = Str::afterLast($attribute, '.');
                break;
            case static::PRESERVE_KEYS_FULL:
            default:
                $last = $attribute;
                break;
        }

        return "$itemIndex.$last";
    }
}
