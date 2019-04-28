<?php declare(strict_types = 1);

namespace Tlapnet\Settus\DI;

use Nette\DI\CompilerExtension;
use Tlapnet\Settus\Component\ISettingsControlFactory;
use Tlapnet\Settus\Component\SettingsControl;
use Tlapnet\Settus\SettingsManager;

class SettusExtension extends CompilerExtension
{

	/** @var mixed[] */
	private $defaults = [
		'managerClass' => SettingsManager::class,
		'sections' => [],
	];

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults);

		$builder->addDefinition($this->prefix('settingsManager'))
			->setFactory($config['managerClass'], [$config['sections']]);

		$builder->addDefinition($this->prefix('settingsControl'))
			->setType(SettingsControl::class)
			->setImplement(ISettingsControlFactory::class);
	}

}
