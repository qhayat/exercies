<?php

namespace App\Service;

class Common
{
    /**
     * Retourne le tableau dans un nouveau tableau
     *
     * @template Value
     * @param array<Value> $array
     * @return array<Value>
     */
    public static function boo(array $array): array
    {
        $result = [];
        array_walk_recursive($array, function ($a) use (&$result) {
            $result[] = $a;
        });

        return $result;
    }

    /**
     * Retourne un tableau depuis $array1 en y ajoutant un nouvel item qui prend en index $array2['k'] et en valeur $array2['v']
     *
     * @param array<int|string> $array1
     * @param array<int|string> $array2
     * @return array<int|string>
     */
    public static function foo(array $array1, array $array2): array
    {
        return [...$array1, $array2['k'] => $array2['v']];
    }

    /**
     * Permet de savoir, si au moin une "key" de $array1 se trouve en tant que valeur dans $array2
     *
     * @param array<int|string> $array1
     * @param array<int|string> $array2
     * @return bool
     */
    public static function bar(array $array1, array $array2): bool
    {
        $r = array_filter(array_keys($array1), function($k) use ($array2) {
            var_dump($k);
            return !in_array($k, $array2);
        });

        return count($r) == 0;
    }
}
