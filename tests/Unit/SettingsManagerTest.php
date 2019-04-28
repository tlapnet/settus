<?php declare(strict_types = 1);

namespace Tests\Tlapnet\Settus\Unit;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tlapnet\Settus\Configurator\NeonConfigurator;
use Tlapnet\Settus\SettingsItem;
use Tlapnet\Settus\SettingsManager;
use Tlapnet\Settus\SettingsSection;
use Tlapnet\Settus\Storage\IStorage;

class SettingsManagerTest extends TestCase
{

	public function testEmpty(): void
	{
		$settingManager = new SettingsManager([]);
		$this->assertEmpty($settingManager->getSections());
	}

	public function testManage(): void
	{
		$storage = Mockery::mock(IStorage::class);
		$configurator = new NeonConfigurator([]);

		$items = [
			'dbname' => [
				'description' => 'Db',
				'default' => 'settus',
			],
		];

		$data = [
			'application' => [
				'storage' => $storage,
				'template' => $items,
			],
			'user' => [
				'storage' => $storage,
				'configurator' => $configurator,
			],
		];

		$settingManager = new SettingsManager($data);
		$this->assertEquals(
			[
				'application' => new SettingsSection(
					$storage,
					[
						'dbname' => new SettingsItem(
							'dbname',
							'Db',
							'settus'
						),
					]
				),
				'user' => (new SettingsSection($storage, [])),
			],
			$settingManager->getSections()
		);
	}

}
