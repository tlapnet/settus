<?php declare(strict_types = 1);

namespace Tlapnet\Settus\DI;

use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use stdClass;
use Tlapnet\Settus\Component\ISettingsControlFactory;
use Tlapnet\Settus\Component\SettingsControl;
use Tlapnet\Settus\SettingsManager;

/**
 * @property-read stdClass $config
 */
class SettusExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'managerClass' => Expect::string(SettingsManager::class),
			'sections' => Expect::array(),
		]);
	}

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->config;

		$builder->addDefinition($this->prefix('settingsManager'))
			->setFactory($config->managerClass, [$config->sections]);

		$settingsControlFactoryDefinition = $builder->addFactoryDefinition($this->prefix('settingsControl'))
			->setImplement(ISettingsControlFactory::class);

		$settingsControlFactoryDefinition->getResultDefinition()
			->setType(SettingsControl::class);
	}

}
