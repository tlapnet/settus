<?php declare(strict_types = 1);

namespace Tlapnet\Settus\Configurator;

use Tlapnet\Settus\Exception\Logical\InvalidArgumentException;
use Tlapnet\Settus\SettingsItem;

class NeonConfigurator implements IConfigurator
{

	/** @var mixed[] */
	private $data;

	/**
	 * @param mixed[] $data
	 */
	public function __construct(array $data)
	{
		$this->data = $data;
	}

	/**
	 * @return SettingsItem[]
	 */
	public function configure(): array
	{
		$items = [];

		foreach ($this->data as $key => $item) {
			if (!is_array($item)) {
				throw new InvalidArgumentException(sprintf('Missing configuration in "%s" settingsItem', $key));
			}

			if (!isset($item['description'])) {
				throw new InvalidArgumentException(sprintf('Missing key "description" in "%s" settingsItem', $key));
			}

			if (!array_key_exists('default', $item)) {
				throw new InvalidArgumentException(sprintf('Missing key "default" in "%s" settingsItem', $key));
			}

			$type = $item['type'] ?? null;
			$settingsItem = new SettingsItem($key, $item['description'], $item['default'], $type);

			if (isset($item['hidden'])) {
				$settingsItem->setHidden(true);
			}

			if (isset($item['control'])) {
				$control = $item['control'];
				$settingsItem->getControl()->setType($control['type']);
				unset($control['type']);
				$settingsItem->getControl()->setMeta($control);
			}

			$items[$key] = $settingsItem;
		}

		return $items;
	}

}
