<?php declare(strict_types = 1);

namespace Tests\Tlapnet\Settus\Unit;

use ArrayIterator;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Tlapnet\Settus\Exception\Logical\InvalidArgumentException;
use Tlapnet\Settus\SettingsItem;
use Tlapnet\Settus\SettingsSection;
use Tlapnet\Settus\Storage\IStorage;

class SettingsSectionTest extends TestCase
{

	public function testGetItem(): void
	{
		$items = [
			'host' => new SettingsItem('host', 'Host', null),
			'database' => new SettingsItem('database', 'Database', null),
			'user' => new SettingsItem('user', 'User', null),
			'password' => new SettingsItem('password', 'Password', null),
		];

		/** @var IStorage|MockInterface $storage */
		$storage = Mockery::mock(IStorage::class);

		$section = new SettingsSection($storage, $items);
		$this->assertSame($items['database'], $section->getItem('database'));

		$this->expectException(InvalidArgumentException::class);
		$section->getItem('unknown');
	}

	public function testGetItems(): void
	{
		$items = [
			'host' => new SettingsItem('host', 'Host', null),
			'database' => new SettingsItem('database', 'Database', null),
			'user' => new SettingsItem('user', 'User', null),
			'password' => new SettingsItem('password', 'Password', null),
		];

		/** @var IStorage|MockInterface $storage */
		$storage = Mockery::mock(IStorage::class);

		$section = new SettingsSection($storage, $items);
		$this->assertSame($items, $section->getItems());
	}

	public function testSetValue(): void
	{
		$host = new SettingsItem('host', 'Host', null);
		$items = [
			'host' => $host,
			'database' => new SettingsItem('database', 'Database', null),
			'user' => new SettingsItem('user', 'User', null),
			'password' => new SettingsItem('password', 'Password', null),
		];

		$save = clone $host;
		$save->setValue('localhost');

		/** @var IStorage|MockInterface $storage */
		$storage = Mockery::mock(IStorage::class)
			->shouldReceive('save')
			->withArgs([['host' => $save]])
			->twice()
			->getMock();

		$section = new SettingsSection($storage, $items);

		$section['host'] = 'localhost';
		$section->offsetSet('host', 'localhost');

		$this->expectException(InvalidArgumentException::class);
		$section['unknown'] = 'value';
	}

	public function testGetValue(): void
	{
		$items = [
			'host' => new SettingsItem('host', 'Host', null),
			'database' => new SettingsItem('database', 'Database', null),
			'user' => new SettingsItem('user', 'User', null),
			'password' => new SettingsItem('password', 'Password', null),
		];

		/** @var IStorage|MockInterface $storage */
		$storage = Mockery::mock(IStorage::class)
			->shouldReceive('load')
			->withArgs([['host' => $items['host']]])
			->getMock();

		$section = new SettingsSection($storage, $items);
		$this->assertSame('', $section->getValue('host'));
	}

	public function testGetValues(): void
	{
		$items = [
			'host' => new SettingsItem('host', 'Host', null),
			'database' => new SettingsItem('database', 'Database', null),
			'user' => new SettingsItem('user', 'User', null),
			'password' => new SettingsItem('password', 'Password', null),
		];

		/** @var IStorage|MockInterface $storage */
		$storage = Mockery::mock(IStorage::class)
			->shouldReceive('load')
			->withArgs([$items])
			->getMock();

		$values = ['host' => '', 'database' => '', 'user' => '', 'password' => ''];

		$section = new SettingsSection($storage, $items);
		$this->assertSame($values, $section->getValues());

		/** @var IStorage|MockInterface $storage */
		$storage = Mockery::mock(IStorage::class)
			->shouldReceive('load')
			->withArgs([['database' => $items['database'], 'password' => $items['password']]])
			->getMock();

		$values = ['database' => '', 'password' => ''];

		$section = new SettingsSection($storage, $items);
		$this->assertSame($values, $section->getValues('database', 'password'));
	}

	public function testOffsetExists(): void
	{
		$items = [
			'host' => new SettingsItem('host', 'Host', null),
			'database' => new SettingsItem('database', 'Database', null),
			'user' => new SettingsItem('user', 'User', null),
			'password' => new SettingsItem('password', 'Password', null),
		];

		/** @var IStorage|MockInterface $storage */
		$storage = Mockery::mock(IStorage::class)
			->shouldReceive('load')
			->withArgs([$items])
			->getMock();

		$section = new SettingsSection($storage, $items);

		$this->assertTrue($section->offsetExists('host'));
		$this->assertTrue($section->offsetExists('database'));
		$this->assertTrue($section->offsetExists('user'));
		$this->assertTrue($section->offsetExists('password'));
		$this->assertFalse($section->offsetExists('unknown'));
	}

	public function testOffsetGet(): void
	{
		$items = [
			'host' => new SettingsItem('host', 'Host', null),
			'database' => new SettingsItem('database', 'Database', null),
			'user' => new SettingsItem('user', 'User', null),
			'password' => new SettingsItem('password', 'Password', null),
		];

		/** @var IStorage|MockInterface $storage */
		$storage = Mockery::mock(IStorage::class)
			->shouldReceive('load')
			->withArgs([['host' => $items['host']]])
			->getMock();

		$section = new SettingsSection($storage, $items);
		$this->assertSame('', $section->offsetGet('host'));
		$this->assertSame('', $section['host']);

		$this->expectException(InvalidArgumentException::class);
		$section['unknown'];
	}

	public function testOffsetSet(): void
	{
		$host = new SettingsItem('host', 'Host', null);
		$items = [
			'host' => $host,
			'database' => new SettingsItem('database', 'Database', null),
			'user' => new SettingsItem('user', 'User', null),
			'password' => new SettingsItem('password', 'Password', null),
		];

		$save = clone $host;
		$save->setValue('localhost');

		/** @var IStorage|MockInterface $storage */
		$storage = Mockery::mock(IStorage::class)
			->shouldReceive('save')
			->withArgs([['host' => $save]])
			->twice()
			->getMock();

		$section = new SettingsSection($storage, $items);

		$section['host'] = 'localhost';
		$section->offsetSet('host', 'localhost');

		$this->expectException(InvalidArgumentException::class);
		$section['unknown'] = 'value';
	}

	public function testOffsetUnset(): void
	{
		$items = [
			'host' => new SettingsItem('host', 'Host', null),
			'database' => new SettingsItem('database', 'Database', null),
			'user' => new SettingsItem('user', 'User', null),
			'password' => new SettingsItem('password', 'Password', null),
		];

		/** @var IStorage|MockInterface $storage */
		$storage = Mockery::mock(IStorage::class);

		$section = new SettingsSection($storage, $items);
		$this->assertSame($items, $section->getItems());
		unset($section['database']);
		$this->assertNotSame($items, $section->getItems());
		unset($items['database']);
		$this->assertSame($items, $section->getItems());
	}

	public function testGetIterator(): void
	{
		$items = [
			'host' => new SettingsItem('host', 'Host', null),
			'database' => new SettingsItem('database', 'Database', null),
			'user' => new SettingsItem('user', 'User', null),
			'password' => new SettingsItem('password', 'Password', null),
		];

		/** @var IStorage|MockInterface $storage */
		$storage = Mockery::mock(IStorage::class)
			->shouldReceive('load')
			->withArgs([$items])
			->getMock();

		$values = ['host' => '', 'database' => '', 'user' => '', 'password' => ''];

		$section = new SettingsSection($storage, $items);
		$this->assertEquals(new ArrayIterator($values), $section->getIterator());
	}

	public function testLoadAll(): void
	{
		$items = [
			'host' => new SettingsItem('host', 'Host', null),
			'database' => new SettingsItem('database', 'Database', null),
			'user' => new SettingsItem('user', 'User', null),
			'password' => new SettingsItem('password', 'Password', null),
		];

		/** @var IStorage|MockInterface $storage */
		$storage = Mockery::mock(IStorage::class)
			->shouldReceive('load')
			->withArgs([$items])
			->getMock();

		$section = new SettingsSection($storage, $items);
		$section->loadAll();

		$this->assertInstanceOf(SettingsSection::class, $section);
	}

}
