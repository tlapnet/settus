<?php declare(strict_types = 1);

namespace Tlapnet\Settus\Component;

use Nette\Application\UI\Form;
use Tlapnet\Settus\SettingsItem;
use Tlapnet\Settus\SettingsItemControl;

class SettingsForm extends Form
{

	/**
	 * @param SettingsItem[] $items
	 */
	public function __construct(array $items)
	{
		parent::__construct();

		foreach ($items as $key => $item) {
			$control = $item->getControl();
			$type = $control->getType();
			$value = $item->getValue();

			$this->addSubmit('save_' . $key, 'Save')
				->setHtmlAttribute('class', 'btn btn-sm btn-primary');

			$this->addSubmit('reset_' . $key, 'Reset')
				->setHtmlAttribute('class', 'btn btn-sm btn-danger');

			if ($type === SettingsItemControl::TYPE_CHECKBOX) {
				$this->addCheckbox('value_' . $key)
					->setHtmlAttribute('class', 'form-control form-control-sm mr-2')
					->setDefaultValue($value);
			} elseif ($type === SettingsItemControl::TYPE_PASSWORD) {
				$this->addPassword('value_' . $key)
					->setHtmlAttribute('class', 'form-control form-control-sm mr-2')
					->setDefaultValue($value);
			} elseif ($type === SettingsItemControl::TYPE_SELECT) {
				$this->addSelect('value_' . $key, null, $control->getMeta()['items'] ?? [])
					->setHtmlAttribute('class', 'form-control form-control-sm mr-2')
					->setDefaultValue($value);
			} else {
				$this->addText('value_' . $key)
					->setHtmlAttribute('class', 'form-control form-control-sm mr-2')
					->setDefaultValue($value);
			}
		}

		$this->addSubmit('saveAll', 'Save all')
			->setHtmlAttribute('class', 'btn btn-sm btn-primary');

		$this->addSubmit('resetAll', 'Reset all')
			->setHtmlAttribute('class', 'btn btn-sm btn-danger');
	}

}
