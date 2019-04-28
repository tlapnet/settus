<?php declare(strict_types = 1);

namespace Tests\Tlapnet\Settus\Unit\Configurator;

use Nette\Neon\Neon;
use Nette\Utils\FileSystem;
use PHPUnit\Framework\TestCase;
use Tlapnet\Settus\Configurator\NeonConfigurator;
use Tlapnet\Settus\SettingsItem;
use Tlapnet\Settus\SettingsItemControl;

class NeonConfiguratorTest extends TestCase
{

	public function testConfigure(): void
	{
		$neon = Neon::decode(FileSystem::read(__DIR__ . '/settings.neon'));
		$configurator = new NeonConfigurator($neon);

		$items = $configurator->configure();
		$this->assertCount(5, $items);
		$this->assertEquals($items['host']->getKey(), 'host');
		$this->assertEquals($items['database']->getKey(), 'database');
		$this->assertEquals($items['user']->getKey(), 'user');
		$this->assertEquals($items['user']->getDefault(), 'root');
		$this->assertEquals($items['password']->getKey(), 'password');
		$this->assertEquals($items['someBool']->getType(), SettingsItem::TYPE_BOOL);
		$this->assertTrue($items['someBool']->isHidden());
		$this->assertSame(SettingsItemControl::TYPE_CHECKBOX, $items['someBool']->getControl()->getType());
		$this->assertSame(['a' => 'b', 'b' => 'a'], $items['someBool']->getControl()->getMeta());
	}

}
