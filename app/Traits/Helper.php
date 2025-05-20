<?php

namespace App\Traits;

trait Helper
{
    public function groupBy(array $lists, string $groupKey): array
    {
        $grouped = [];

        foreach ($lists as $list) {
            $key = is_array($list) ? $list[$groupKey] : $list->$groupKey;
            $grouped[$key][] = $list;
        }

        return $grouped;
    }

    public function groupByTwoLevels(array $lists, string $firstKey, string $secondKey): array
    {
        $grouped = [];

        foreach ($lists as $item) {
            $key1 = is_array($item) ? $item[$firstKey] : $item->$firstKey;
            $key2 = is_array($item) ? $item[$secondKey] : $item->$secondKey;

            $grouped[$key1][$key2][] = $item;
        }

        return $grouped;
    }
}