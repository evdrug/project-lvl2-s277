<?php
namespace Differ\Reports;

function reportPlain($data)
{
    $report = function ($data, $parent = '') use (&$report) {
        return array_reduce($data, function ($acc, $item) use ($report, $parent) {
            [
                'property' => $property,
                'before' => $rawBefore,
                'after' => $rawAfter,
                'action' => $action,
                'children' => $children
            ] = $item;
            $after = normalizeValue($rawAfter);
            $before = normalizeValue($rawBefore);
            switch ($action) {
                case 'nested':
                    $nameParent = "{$property}.{$parent}";
                    $acc[] = join(PHP_EOL, $report($children, $nameParent));
                    break;
                case 'removed':
                    $value = "{$property}";
                    $acc[] = "Property '{$parent}{$value}' was removed";
                    break;
                case 'added':
                    $value = is_array($after) ? "complex value" : $after ;
                    $acc[] = "Property '{$parent}{$property}' was added with value: '{$value}'";
                    break;
                case 'changed':
                    $valueAfter = is_array($after) ? "complex value" : $after ;
                    $valueBefore = is_array($before) ? "complex value" : $before ;
                    $acc[] = "Property '{$parent}{$property}' was changed. From '{$valueBefore}' to '{$valueAfter}'";
                    break;
            }
            return $acc;
        }, []);
    };

    return join(PHP_EOL, $report($data)).PHP_EOL;
}
