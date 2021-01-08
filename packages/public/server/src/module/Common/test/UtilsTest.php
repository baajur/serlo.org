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
namespace CommonTest;

use Common\Utils;
use PHPUnit\Framework\TestCase;

class UtilsArrayEveryTest extends TestCase
{
    private $isEven;

    public function setUp()
    {
        $this->isEven = function ($x) {
            return $x % 2 === 0;
        };
    }

    public function testReturnsTrueIfAllTestSucceed()
    {
        $this->assertTrue(Utils::array_every($this->isEven, [2, 4, 6]));
    }

    public function testReturnsFalseIfOneTestFails()
    {
        $this->assertFalse(Utils::array_every($this->isEven, [2, 3, 4]));
    }

    public function testReturnsTrueIfArrayIsEmpty()
    {
        $this->assertTrue(Utils::array_every(function () {}, []));
    }
}

class UtilsArrayFlatmapTest extends TestCase
{
    private $duplicateFunc;

    public function setUp()
    {
        $this->duplicateFunc = function ($x): array {
            return [$x, $x];
        };
    }

    public function testWithDuplicationForSingletonArray()
    {
        $input = [1];
        $expected = [1, 1];

        $this->assertEquals(
            Utils::array_flatmap($this->duplicateFunc, $input),
            $expected
        );
    }

    public function testWithDuplicationForMixedArray()
    {
        $input = ['x', true, 42];
        $expected = ['x', 'x', true, true, 42, 42];

        $this->assertEquals(
            Utils::array_flatmap($this->duplicateFunc, $input),
            $expected
        );
    }

    public function testReturnsEmptySetForEmptySet()
    {
        $this->assertEmpty(Utils::array_flatmap(function () {}, []));
    }
}

class UtilsArraySomeTest extends TestCase
{
    private $isEven;

    public function setUp()
    {
        $this->isEven = function ($x) {
            return $x % 2 === 0;
        };
    }

    public function testReturnsTrueIfSomeTestSucceed()
    {
        $this->assertTrue(Utils::array_some($this->isEven, [1, 2, 3]));
    }

    public function testReturnsFalseIfAllTestFails()
    {
        $this->assertFalse(Utils::array_some($this->isEven, [1, 3, 5]));
    }

    public function testReturnsFalseIfArrayIsEmpty()
    {
        $this->assertFalse(Utils::array_some(function () {}, []));
    }
}
