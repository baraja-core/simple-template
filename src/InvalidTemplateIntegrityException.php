<?php

declare(strict_types=1);

namespace Baraja\SimpleTemplate;


final class InvalidTemplateIntegrityException extends \LogicException
{
	/** @var array<int, string> */
	private array $errors = [];


	public function addError(string $message, ?int $line = null): void
	{
		if ($line !== null) {
			$message .= sprintf(' [on line %d]', $line);
		}
		$this->errors[] = $message;
	}


	/**
	 * @return array<int, string>
	 */
	public function getErrors(): array
	{
		return $this->errors;
	}


	public function hasInternalErrors(): bool
	{
		return $this->errors !== [];
	}
}
