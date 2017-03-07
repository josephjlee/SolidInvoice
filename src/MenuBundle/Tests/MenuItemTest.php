<?php

/*
 * This file is part of CSBill project.
 *
 * (c) 2013-2016 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CSBill\MenuBundle\Tests;

use CSBill\MenuBundle\MenuItem;
use Knp\Menu\Factory\CoreExtension;
use Mockery as M;

class MenuItemTest extends \PHPUnit\Framework\TestCase
{
    public function testAddDivider()
    {
        $factory = M::mock('Knp\Menu\FactoryInterface');
        $item = new MenuItem('test', $factory);

        $childItem = new MenuItem(null, $factory);
        $childItem->setExtra('divider', '-*');
        $factory->shouldReceive('createItem')
            ->withAnyArgs()
            ->andReturn($childItem);

        $child = $item->addDivider('*');

        $this->assertInstanceOf('CSBill\MenuBundle\MenuItem', $child);
        $this->assertTrue($child->isDivider());
        $this->assertSame('-*', $child->getExtra('divider'));
    }

    public function testAddChild()
    {
        $factory = M::mock('Knp\Menu\FactoryInterface');
        $item = new MenuItem('test', $factory);

        $factory->shouldReceive('createItem')
            ->with('abc', [])
            ->andReturn(new MenuItem('abc', $factory));

        $child = $item->addChild('abc');

        $this->assertInstanceOf('CSBill\MenuBundle\MenuItem', $child);
    }

    public function testAddChildArray()
    {
        $factory = M::mock('Knp\Menu\FactoryInterface');
        $item = new MenuItem('test', $factory);

        $factory->shouldReceive('createItem')
            ->with('abc', [])
            ->andReturn(new MenuItem('abc', $factory));

        $child = $item->addChild(['abc', []]);

        $this->assertInstanceOf('CSBill\MenuBundle\MenuItem', $child);
    }

    public function testIsDivider()
    {
        $coreExtension = new CoreExtension();
        $item = new MenuItem('test', M::mock('Knp\Menu\FactoryInterface'));

        $options = ['extras' => ['divider' => true]];

        $coreExtension->buildItem($item, $coreExtension->buildOptions($options));

        $this->assertTrue($item->isDivider());
    }

    public function testIsDividerFalse()
    {
        $coreExtension = new CoreExtension();
        $item = new MenuItem('test', M::mock('Knp\Menu\FactoryInterface'));

        $coreExtension->buildItem($item, $coreExtension->buildOptions([]));

        $this->assertFalse($item->isDivider());
    }
}