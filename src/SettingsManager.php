<?php declare(strict_types = 1);

namespace Tlapnet\Settus;

use Tlapnet\Settus\Configurator\IConfigurator;
use Tlapnet\Settus\Configurator\NeonConfigurator;
use Tlapnet\Settus\Exception\Logical\InvalidArgumentException;

class SettingsManager
{

	/** @var SettingsSection[] */
	private $sections = [];

	/**
	 * @param mixed[] $configuration
	 */
	public function __construct(array $configuration)
	{
		foreach ($configuration as $key => $item) {
			if (isset($item['template'])) {
				$configurator = new NeonConfigurator($item['template']);
			} elseif (!isset($item['configurator'])) {
				throw new InvalidArgumentException('Configurator is not set');
			} elseif (!$item['configurator'] instanceof IConfigurator) {
				throw new InvalidArgumentException('Configurator is instance of IConfigurator');
			} else {
				/** @var IConfigurator $configurator */
				$configurator = $item['configurator'];
			}

			$settings = $configurator->configure();
			$section = new SettingsSection($item['storage'], $settings);
			$this->addSection($key, $section);
		}
	}

	public function addSection(string $key, SettingsSection $section): void
	{
		$this->sections[$key] = $section;
	}

	public function getSection(string $key): SettingsSection
	{
		if (!$this->hasSection($key)) {
			throw new InvalidArgumentException(sprintf('Section with key "%s" not exist', $key));
		}

		return $this->sections[$key];
	}

	public function hasSection(string $key): bool
	{
		return isset($this->sections[$key]);
	}

	/**
	 * @return SettingsSection[]
	 */
	public function getSections(): array
	{
		return $this->sections;
	}

}
