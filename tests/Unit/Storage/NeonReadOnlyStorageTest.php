<?php declare(strict_types = 1);

namespace Tests\Tlapnet\Settus\Unit\Storage;

use Nette\Neon\Neon;
use Nette\Utils\FileSystem;
use PHPUnit\Framework\TestCase;
use Tlapnet\Settus\Exception\Logical\InvalidStateException;
use Tlapnet\Settus\SettingsItem;
use Tlapnet\Settus\Storage\NeonReadOnlyStorage;

class NeonReadOnlyStorageTest extends TestCase
{

	public function testLoad(): void
	{
		$settings = [
			'host' => new SettingsItem('host', 'Host', null),
			'database' => new SettingsItem('database', 'Database', null),
			'user' => new SettingsItem('user', 'User', null),
			'password' => new SettingsItem('password', 'Password', null),
		];

		$neon = Neon::decode(FileSystem::read(__DIR__ . '/values.neon'));
		$loader = new NeonReadOnlyStorage($neon);
		$loader->load($settings);

		$this->assertCount(4, $settings);
		$this->assertEquals($settings['host']->getValue(), 'localhost');
		$this->assertEquals($settings['database']->getValue(), 'project');
		$this->assertEquals($settings['user']->getValue(), 'root');
		$this->assertEquals($settings['password']->getValue(), null);
	}

	public function testSave(): void
	{
		$this->expectException(InvalidStateException::class);
		$loader = new NeonReadOnlyStorage([]);
		$loader->save(['host' => new SettingsItem('host', 'Host', null)]);
	}

}
