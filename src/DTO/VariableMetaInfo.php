<?php

declare(strict_types=1);

namespace Baraja\SimpleTemplate\DTO;


use Baraja\SimpleTemplate\TemplateData;

final class VariableMetaInfo
{
	public function __construct(
		private TemplateData $data,
		private \ReflectionMethod $ref,
		private string $name,
		private string $methodName,
	) {
	}


	public function getRealValue(): mixed
	{
		return $this->ref->invoke($this->data);
	}


	public function renderRealValue(): string
	{
		$value = $this->getRealValue();
		if (is_scalar($value) || $value === null) {
			return htmlspecialchars((string) $value);
		}
		if ($value instanceof HTML) {
			return (string) $value;
		}

		throw new \LogicException(sprintf('Type "%s" can not be casted to string.', get_debug_type($value)));
	}


	public function getRef(): \ReflectionMethod
	{
		return $this->ref;
	}


	public function getName(): string
	{
		return $this->name;
	}


	public function getMethodName(): string
	{
		return $this->methodName;
	}


	public function getDocumentation(): ?string
	{
		$doc = trim((string) $this->ref->getDocComment());
		$doc = str_replace(["\r\n", "\r"], "\n", trim($doc));
		if ($doc !== '') {
			$doc = str_replace(['/**', '/*', '*/'], '', $doc);
			$return = '';
			foreach (explode("\n", $doc) as $line) {
				$line = trim($line, "\ \t\n\r\0\x0B*");
				if ($line !== '') {
					$return .= ' ' . $line;
				}
			}
			$doc = trim($return);
		}

		return $doc !== '' ? $doc : null;
	}
}
