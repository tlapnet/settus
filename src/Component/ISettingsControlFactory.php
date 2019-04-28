<?php declare(strict_types = 1);

namespace Tlapnet\Settus\Component;

use Tlapnet\Settus\SettingsSection;

interface ISettingsControlFactory
{

	public function create(SettingsSection $section): SettingsControl;

}
