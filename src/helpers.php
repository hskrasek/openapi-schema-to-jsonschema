<?php declare(strict_types=1);

namespace HSkrasek\OpenAPI;

/**
 * Get an item from an array or object using "dot" notation.
 *
 * @param  mixed $target
 * @param  string|array $key
 * @param  mixed $default
 *
 * @return mixed
 *
 * @source Laravel (Thanks Taylor!)
 */
function data_get($target, $key, $default = null)
{
    if (null === $key) {
        return $target;
    }

    $key = \is_array($key) ? $key : explode('.', $key);

    while (null !== $segment = array_shift($key)) {
        if (\is_array($target) && array_key_exists($segment, $target)) {
            $target = $target[$segment];
        } elseif (\is_object($target) && isset($target->{$segment})) {
            $target = $target->{$segment};
        } else {
            return $default;
        }
    }

    return $target;
}

/**
 * Set an item on an array or object using dot notation.
 *
 * @param  mixed $target
 * @param  string|array $key
 * @param  mixed $value
 * @param  bool $overwrite
 *
 * @return mixed
 *
 * @source Laravel (Thanks Taylor!)
 */
function data_set(&$target, $key, $value, $overwrite = true)
{
    $segments = \is_array($key) ? $key : explode('.', $key);

    $segment = array_shift($segments);

    if (\is_array($target)) {
        if ($segments) {
            if (!array_key_exists($segment, $target)) {
                $target[$segment] = [];
            }

            data_set($target[$segment], $segments, $value, $overwrite);
        } elseif ($overwrite || !array_key_exists($segment, $target)) {
            $target[$segment] = $value;
        }
    } elseif (\is_object($target)) {
        if ($segments) {
            if (!isset($target->{$segment})) {
                $target->{$segment} = [];
            }

            data_set($target->{$segment}, $segments, $value, $overwrite);
        } elseif ($overwrite || !isset($target->{$segment})) {
            $target->{$segment} = $value;
        }
    } else {
        $target = [];

        if ($segments) {
            data_set($target[$segment], $segments, $value, $overwrite);
        } elseif ($overwrite) {
            $target[$segment] = $value;
        }
    }

    return $target;
}
