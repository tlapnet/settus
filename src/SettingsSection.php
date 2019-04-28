<?php declare(strict_types = 1);

namespace Tlapnet\Settus;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Tlapnet\Settus\Exception\Logical\InvalidArgumentException;
use Tlapnet\Settus\Storage\IStorage;

class SettingsSection implements ArrayAccess, IteratorAggregate
{

	/** @var IStorage */
	private $storage;

	/** @var SettingsItem[] */
	private $items = [];

	/**
	 * @param SettingsItem[] $items
	 */
	public function __construct(IStorage $storage, array $items)
	{
		$this->storage = $storage;
		$this->items = $items;
	}

	/**
	 * @param mixed $key
	 */
	public function getItem($key): SettingsItem
	{
		$item = $this->items[$key] ?? null;

		if ($item === null) {
			throw new InvalidArgumentException(sprintf('Missing settings item "%s"', $key));
		}

		return $item;
	}

	/**
	 * @return SettingsItem[]
	 */
	public function getItems(): array
	{
		return $this->items;
	}

	/**
	 * @param mixed $key
	 * @param mixed $value
	 */
	public function setValue($key, $value): void
	{
		$item = $this->getItem($key);
		$item->setValue($value);
		$this->storage->save([$key => $item]);
	}

	/**
	 * @param mixed $key
	 */
	public function resetValue($key): void
	{
		$item = $this->getItem($key);
		$item->reset();
		$this->storage->save([$item]);
	}

	public function resetAll(): void
	{
		foreach ($this->items as $item) {
			$item->reset();
		}

		$this->storage->save($this->items);
	}

	/**
	 * @param mixed $key
	 * @return mixed
	 */
	public function getValue($key)
	{
		$item = $this->getItem($key);

		if (!$item->isLoaded()) {
			$this->storage->load([$key => $item]);
		}

		return $item->getValue();
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 * @param mixed[] ...$keys
	 * @return mixed[]
	 */
	public function getValues(...$keys): array
	{
		if ($keys === []) {
			$keys = array_keys($this->items);
		}

		$needLoad = [];

		foreach ($keys as $key) {
			$item = $this->getItem($key);

			if (!$item->isLoaded()) {
				$needLoad[$key] = $item;
			}
		}

		$this->storage->load($needLoad);
		$values = [];

		foreach ($keys as $key) {
			$item = $this->getItem($key);
			$values[$key] = $item->getValue();
		}

		return $values;
	}

	/**
	 * @param mixed $offset
	 */
	public function offsetExists($offset): bool
	{
		return array_key_exists($offset, $this->items);
	}

	/**
	 * @param mixed $offset
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		if ($this->offsetExists($offset)) {
			return $this->getValue($offset);
		}

		throw new InvalidArgumentException(sprintf('Missing settings item "%s"', $offset));
	}

	/**
	 * @param mixed $offset
	 * @param mixed $value
	 */
	public function offsetSet($offset, $value): void
	{
		$this->setValue($offset, $value);
	}

	/**
	 * @param mixed $offset
	 */
	public function offsetUnset($offset): void
	{
		unset($this->items[$offset]);
	}

	public function getIterator(): ArrayIterator
	{
		return new ArrayIterator($this->getValues());
	}

	public function loadAll(): void
	{
		$this->storage->load($this->items);
	}

}
