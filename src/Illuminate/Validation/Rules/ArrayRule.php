<?php

namespace Illuminate\Validation\Rules;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Traits\Conditionable;
use Stringable;

use function Illuminate\Support\enum_value;

class ArrayRule implements Stringable
{
    use Conditionable;

    /**
     * The accepted keys.
     *
     * @var array
     */
    protected $keys;

    /**
     * The constraints for the array rule.
     */
    protected array $constraints = [];

    /**
     * Create a new array rule instance.
     *
     * @param  \Illuminate\Contracts\Support\Arrayable|array|null  $keys
     */
    public function __construct($keys = null)
    {
        if ($keys instanceof Arrayable) {
            $keys = $keys->toArray();
        }

        $this->keys = is_array($keys) ? $keys : func_get_args();
    }

    /**
     * The array under validation must contain between the given minimum and maximum number of items.
     *
     * @param  int  $min
     * @param  int  $max
     * @return $this
     */
    public function between(int $min, int $max): static
    {
        return $this->addRule('between:'.$min.','.$max);
    }

    /**
     * The array under validation must contain the given number of items.
     *
     * @param  int  $value
     * @return $this
     */
    public function exactly(int $value): static
    {
        return $this->addRule('size:'.$value);
    }

    /**
     * The array under validation must contain no more than the given number of items.
     *
     * @param  int  $value
     * @return $this
     */
    public function max(int $value): static
    {
        return $this->addRule('max:'.$value);
    }

    /**
     * The array under validation must contain at least the given number of items.
     *
     * @param  int  $value
     * @return $this
     */
    public function min(int $value): static
    {
        return $this->addRule('min:'.$value);
    }

    /**
     * Convert the rule to a validation string.
     *
     * @return string
     */
    public function __toString()
    {
        $rule = 'array';

        if (! empty($this->keys)) {
            $keys = array_map(
                static fn ($key) => enum_value($key),
                $this->keys,
            );

            $rule .= ':'.implode(',', $keys);
        }

        return implode('|', [$rule, ...$this->constraints]);
    }

    /**
     * Add custom rules to the validation rules array.
     */
    protected function addRule(array|string $rules): static
    {
        $this->constraints = array_merge($this->constraints, Arr::wrap($rules));

        return $this;
    }
}
