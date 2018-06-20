<?php

namespace Differ\Reports;

use function Funct\Collection\flatten;

function jsonReport($data)
{
    return json_encode($data, JSON_FORCE_OBJECT);
}

function normalizeBool($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    return $value;
}

function prettyReport($data)
{
    $childArray = function ($iter, $func) {
        var_dump($iter);
        return array_reduce(array_keys($iter), function ($acc, $key) use ($iter, $func) {
            $acc .= PHP_EOL."{$func}{$key}: {$iter[$key]}";
            return $acc;
        }, '');
    };

    $result = function ($data, $repit) use (&$result, $childArray) {
        $repit = $repit + 1;
        $indent = function ($position) use ($repit) {
            return str_repeat(' ', $position * 2+2);
        };
        return array_reduce($data, function ($acc, $item) use ($result, $childArray, $indent, $repit) {
            switch ($item['action']) {
                case 'nested':
                    var_dump($item['children']);
                    $resultChild = $result($item['children'], $repit);
                    $acc[] = "{$indent($repit)}{$item['property']}: {";
                    $acc[] = $resultChild;
                    $acc[] = "{$indent($repit)}}";
                    break;
                case 'not changed':
                    $before = normalizeBool($item['before']);
                    $acc[] = "{$indent($repit + 1)}{$item['property']}: {$before}";
                    break;
                case 'changed':
                    $before = normalizeBool($item['before']);
                    $after = normalizeBool($item['after']);
                    $acc[] = "{$indent($repit)}- {$item['property']}: {$before}";
                    $acc[] = "{$indent($repit)}+ {$item['property']}: {$after}";
                    break;
                case 'removed':
                    $before = normalizeBool($item['before']);
                    if (is_array($before)) {
                        $child = $childArray($before, $indent($repit + 2));
                        $acc[] = "{$indent($repit)}- {$item['property']}: {{$child}";
                        $acc[] = "{$indent($repit)}  }";
                    } else {
                        $acc[] = "{$indent($repit)}- {$item['property']}: {$before}";
                    }
                    break;
                case 'added':
                    $after = normalizeBool($item['after']);
                    if (is_array($after)) {
                        $child = $childArray($after, $indent($repit + 2));
                        $acc[] = "{$indent($repit)}+ {$item['property']}: {{$child}";
                        $acc[] = "{$indent($repit)}  }";
                    } else {
                        $acc[] = "{$indent($repit)}+ {$item['property']}: {$after}";
                    }
                    break;
            }
            return $acc;
        }, []);
    } ;
//    print_r("{".PHP_EOL.join(flatten(flatten($result($data, 0))), PHP_EOL).PHP_EOL."}".PHP_EOL);
    $responseDiff = "{".PHP_EOL.join($result($data, 0), PHP_EOL).PHP_EOL."}".PHP_EOL;
    return $responseDiff;
}

function plainReport($data)
{
    return array_reduce($data, function ($acc, $item) {
        switch ($item['action']) {
            case 'not changed':
                $acc[] = "  {$item['property']}: {$item['before']}";
                break;
            case 'changed':
                $acc[] = "- {$item['property']}: {$item['before']}";
                $acc[] = "+ {$item['property']}: {$item['after']}";
                break;
            case 'removed':
                $acc[] = "- {$item['property']}: {$item['before']}";
                break;
            case 'added':
                $acc[] = "+ {$item['property']}: {$item['after']}";
                break;
        }
        return $acc;
    }, []);
}
