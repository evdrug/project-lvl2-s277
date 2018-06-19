<?php

namespace Differ\Reports;

function jsonReport($data)
{
    return json_encode($data, JSON_FORCE_OBJECT);
}

function prettyReport($data)
{
    $childArray = function ($iter) {
        return array_reduce(array_keys($iter), function ($acc, $key) use ($iter) {
            var_dump($key);
            $acc .= PHP_EOL."{$key}: {$iter[$key]}".PHP_EOL;
            return $acc;
        }, '');
    };
    $result = function ($data, $repit) use (&$result, $childArray) {
        $repit = $repit + 1;
        $indent = function ($position) use ($repit) {
            return str_repeat(' ', $repit * $position);
        };
        return array_reduce($data, function ($acc, $item) use ($result, $childArray, $indent, $repit) {
            switch ($item['action']) {
                case 'nested':
                    $resultChild = $result($item['children'], $repit);
                    $acc[] = "{$indent(4)}{$item['property']}: {";
                    $acc[] = $resultChild;
                    $acc[] = "}";
                    break;
                case 'not changed':
                    $acc[] = "{$indent(4)}{$item['property']}: {$item['before']}";
                    break;
                case 'changed':
                    $acc[] = "{$indent(2)}- {$item['property']}: {$item['before']}";
                    $acc[] = "{$indent(2)}+ {$item['property']}: {$item['after']}";
                    break;
                case 'removed':
                    if (is_array($item['before'])) {
//                        $child = $childArray($item['before']);
//                        $acc[] = "- {$item['property']}: {{$child}}";
                    } else {
                        $acc[] = "{$indent(2)}- {$item['property']}: {$item['before']}";
                    }
                    break;
                case 'added':
                    if (is_array($item['after'])) {
//                        $child = $childArray($item['after']);
//                        $acc[] = "+ {$item['property']}: {{$child}}";
                    } else {
                        $acc[] = "{$indent(2)}+ {$item['property']}: {$item['after']}";
                    }
                    break;
            }
            return $acc;
        }, []);
    } ;
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
