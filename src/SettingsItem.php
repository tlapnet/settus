<?php declare(strict_types = 1);

namespace Tlapnet\Settus;

class SettingsItem
{

	public const TYPE_STRING = 'string';
	public const TYPE_INT = 'int';
	public const TYPE_FLOAT = 'float';
	public const TYPE_BOOL = 'bool';

	/** @var string */
	private $key;

	/** @var string */
	private $description;

	/** @var mixed */
	private $default;

	/** @var mixed */
	private $value;

	/** @var string */
	private $type;

	/** @var bool */
	private $hidden = false;

	/** @var bool */
	private $loaded = false;

	/** @var SettingsItemControl */
	private $control;

	/**
	 * @param mixed $default
	 */
	public function __construct(string $key, string $description, $default, ?string $type = null)
	{
		$this->key = $key;
		$this->description = $description;
		$this->default = $default;
		$this->type = $type ?? self::TYPE_STRING;
		$this->control = new SettingsItemControl();
	}

	public function getKey(): string
	{
		return $this->key;
	}

	public function getDescription(): string
	{
		return $this->description;
	}

	public function getType(): string
	{
		return $this->type;
	}

	/**
	 * @return mixed
	 */
	public function getDefault()
	{
		return $this->default;
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		if ($this->type === self::TYPE_STRING) {
			return (string) $this->value;
		} elseif ($this->type === self::TYPE_INT) {
			return (int) $this->value;
		} elseif ($this->type === self::TYPE_FLOAT) {
			return (float) $this->value;
		} elseif ($this->type === self::TYPE_BOOL) {
			return (bool) $this->value;
		}

		return $this->value;
	}

	/**
	 * @param mixed $value
	 */
	public function setValue($value): void
	{
		$this->loaded = true;
		$this->value = $value;
	}

	public function isHidden(): bool
	{
		return $this->hidden;
	}

	public function setHidden(bool $hidden): void
	{
		$this->hidden = $hidden;
	}

	public function getControl(): SettingsItemControl
	{
		return $this->control;
	}

	public function isChanged(): bool
	{
		return $this->default !== $this->getValue();
	}

	public function isLoaded(): bool
	{
		return $this->loaded;
	}

	public function reset(): void
	{
		$this->value = $this->default;
	}

	public function __toString(): string
	{
		if ($this->value === null) {
			if ($this->type === self::TYPE_INT) {
				return '0';
			}

			if ($this->type === self::TYPE_FLOAT) {
				return '0';
			}

			if ($this->type === self::TYPE_BOOL) {
				return '0';
			}
		}

		return (string) $this->value;
	}

}
