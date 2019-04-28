<?php declare(strict_types = 1);

namespace Tlapnet\Settus\Storage;

use Tlapnet\Settus\Exception\Logical\InvalidStateException;
use Tlapnet\Settus\SettingsItem;

class NeonReadOnlyStorage implements IStorage
{

	/** @var mixed[] */
	private $data = [];

	/**
	 * @param mixed[] $data
	 */
	public function __construct(array $data)
	{
		$this->data = $data;
	}

	/**
	 * @param SettingsItem[] $items
	 * @return SettingsItem[]
	 */
	public function load(array $items): array
	{
		foreach ($items as $item) {
			$key = $item->getKey();

			if (array_key_exists($key, $this->data)) {
				$item->setValue($this->data[$key]);
			}
		}

		return $items;
	}

	/**
	 * @param SettingsItem[] $items
	 */
	public function save(array $items): void
	{
		throw new InvalidStateException('Read-only storage can\'t save data');
	}

}
