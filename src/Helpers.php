<?php

declare(strict_types=1);

namespace Baraja\SimpleTemplate;


final class Helpers
{
	public static function methodNameToVariableName(string $name): string
	{
		if (preg_match('/^(get|is)([a-zA-Z0-9]+)$/', $name, $parser) === 1) {
			[, $type, $label] = $parser;
			$label = strtolower(mb_substr($label, 0, 1, 'UTF-8')) . mb_substr($label, 1, null, 'UTF-8');
			if (isset($label[0]) === false) {
				throw new \InvalidArgumentException('Method first char can not be empty.');
			}

			return $label;
		}

		throw new \InvalidArgumentException(
			sprintf('Method name "%s" is not in valid format. Did you start name with "get" or "is"?', $name),
		);
	}
}
