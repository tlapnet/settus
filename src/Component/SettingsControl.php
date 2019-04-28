<?php declare(strict_types = 1);

namespace Tlapnet\Settus\Component;

use Nette\Application\UI\Control;
use Nette\Application\UI\ITemplate;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Forms\Controls\SubmitButton;
use Nette\Utils\Strings;
use Tlapnet\Settus\SettingsSection;

/**
 * @property-read ITemplate|Template $template
 */
class SettingsControl extends Control
{

	/** @var callable[] */
	public $onChange = [];

	/** @var bool */
	private $readOnly = false;

	/** @var bool */
	private $showDefault = true;

	/** @var SettingsSection */
	private $section;

	public function __construct(SettingsSection $section)
	{
		parent::__construct();
		$this->section = $section;
	}

	public function setReadOnly(bool $readOnly): void
	{
		$this->readOnly = $readOnly;
	}

	public function setShowDefault(bool $showDefault): void
	{
		$this->showDefault = $showDefault;
	}

	protected function createComponentForm(): SettingsForm
	{
		$form = new SettingsForm($this->section->getItems());
		$form->onSubmit[] = [$this, 'processForm'];

		return $form;
	}

	public function processForm(SettingsForm $form): void
	{
		if ($this->readOnly) {
			return;
		}

		$values = $form->getValues();

		/** @var SubmitButton $submitter */
		$submitter = $form->isSubmitted();
		$submitterName = $submitter->name;

		// Save all
		if ($submitterName === 'saveAll') {
			foreach ($this->section->getItems() as $key => $item) {
				$valueItem = 'value_' . $key;
				$this->section->setValue($key, $values->{$valueItem});
			}

			$this->onChange();

			return;
		}

		// Reset all
		if ($submitterName === 'resetAll') {
			$this->section->resetAll();
			$this->onChange();

			return;
		}

		// Reset one
		if (Strings::startsWith($submitterName, 'reset_')) {
			$type = Strings::substring($submitterName, 6);
			$this->section->resetValue($type);
			$this->onChange();

			return;
		}

		// Save
		if (Strings::startsWith($submitterName, 'save_')) {
			$type = Strings::substring($submitterName, 5);
			$valueItem = 'value_' . $type;
			$this->section->setValue($type, $values->{$valueItem});
			$this->onChange();

			return;
		}
	}

	public function render(): void
	{
		$this->section->loadAll();
		$this->template->readOnly = $this->readOnly;
		$this->template->showDefault = $this->showDefault;
		$this->template->items = $this->section->getItems();
		$this->template->setFile(__DIR__ . '/templates/settingControl.latte');
		$this->template->render();
	}

}
