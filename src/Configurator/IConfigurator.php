<?php declare(strict_types = 1);

namespace Tlapnet\Settus\Configurator;

use Tlapnet\Settus\SettingsItem;

interface IConfigurator
{

	/**
	 * @return SettingsItem[]
	 */
	public function configure(): array;

}
