<?php
/**
 * This file is part of Serlo.org.
 *
 * Copyright (c) 2013-2021 Serlo Education e.V.
 *
 * Licensed under the Apache License, Version 2.0 (the "License")
 * you may not use this file except in compliance with the License
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @copyright Copyright (c) 2013-2021 Serlo Education e.V.
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @link      https://github.com/serlo-org/serlo.org for the canonical source repository
 */
namespace Common;

abstract class Utils
{
    public static function array_every(callable $testFunc, array $array): bool
    {
        return array_product(array_map($testFunc, $array));
    }

    public static function array_flatmap(callable $map, array $array): array
    {
        return empty($array) ? [] : array_merge(...array_map($map, $array));
    }

    public static function array_some(callable $testFunc, array $array): bool
    {
        return array_sum(array_map($testFunc, $array)) > 0;
    }

    public static function array_union(array $array1, array $array2): array
    {
        return array_unique(array_merge($array1, $array2));
    }
}
