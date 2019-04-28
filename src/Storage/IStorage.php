<?php declare(strict_types = 1);

namespace Tlapnet\Settus\Storage;

use Tlapnet\Settus\SettingsItem;

interface IStorage
{

	/**
	 * @param SettingsItem[] $items
	 * @return SettingsItem[]
	 */
	public function load(array $items): array;

	/**
	 * @param SettingsItem[] $items
	 */
	public function save(array $items): void;

}
