<?php declare(strict_types = 1);

namespace Tlapnet\Settus;

class SettingsItemControl
{

	public const TYPE_TEXT = 'text';
	public const TYPE_PASSWORD = 'password';
	public const TYPE_CHECKBOX = 'checkbox';
	public const TYPE_SELECT = 'select';

	/** @var string */
	private $type;

	/** @var mixed[] */
	private $meta = [];

	/**
	 * @param mixed[] $meta
	 */
	public function __construct(string $type = self::TYPE_TEXT, array $meta = [])
	{
		$this->type = $type;
		$this->meta = $meta;
	}

	public function setType(string $type): void
	{
		$this->type = $type;
	}

	public function getType(): string
	{
		return $this->type;
	}

	/**
	 * @param mixed[] $meta
	 */
	public function setMeta(array $meta): void
	{
		$this->meta = $meta;
	}

	/**
	 * @return mixed[]
	 */
	public function getMeta(): array
	{
		return $this->meta;
	}

}
