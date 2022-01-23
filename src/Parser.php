<?php

declare(strict_types=1);

namespace Baraja\SimpleTemplate;


use Baraja\SimpleTemplate\DTO\VariableMetaInfo;

final class Parser
{
	/**
	 * @return array<string, VariableMetaInfo>
	 * @throws InvalidTemplateIntegrityException
	 */
	public function parseAvailableVariables(TemplateData $data): array
	{
		$ref = new \ReflectionClass($data);
		$error = new InvalidTemplateIntegrityException;
		$return = [];
		foreach ($ref->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
			$name = $method->getName();
			$returnType = $method->getReturnType();
			if (str_starts_with($name, '__')) {
				continue;
			}
			if ($method->isStatic()) {
				$error->addError(
					sprintf(
						'Template data object can not implement static methods, because it can break context. But method "%s" has been implemented.',
						$name,
					)
				);
				continue;
			}
			if ($returnType === null) {
				$error->addError(sprintf('Method "%s" does not implement strict return type.', $name));
				continue;
			}
			try {
				$variableName = Helpers::methodNameToVariableName($name);
			} catch (\InvalidArgumentException $e) {
				$error->addError($e->getMessage());
				continue;
			}
			$return[$variableName] = new VariableMetaInfo(
				data: $data,
				ref: $method,
				name: $variableName,
				methodName: $name,
			);
		}
		if ($error->hasInternalErrors()) {
			throw $error;
		}

		return $return;
	}


	/**
	 * @return array<int, string>
	 * @throws InvalidTemplateIntegrityException
	 */
	public function parseUsedVariables(string $template): array
	{
		$return = [];
		if (preg_match_all('/\{\{\s*([^}]*?)\s*}}/', $template, $parser) > 0) {
			foreach ($parser[1] ?? [] as $variable) {
				if ($variable === '') {
					throw new InvalidTemplateIntegrityException('Variable name can not be empty.');
				}
				if (preg_match('/^[a-zA-Z0-9]+$/', $variable) !== 1) {
					throw new InvalidTemplateIntegrityException(sprintf('Variable name "%s" is not valid.', $variable));
				}
				$return[] = $variable;
			}
		}

		return array_unique($return);
	}


	/**
	 * @return never-return
	 * @throws InvalidTemplateIntegrityException
	 */
	private function error(string $message): void
	{
		throw new InvalidTemplateIntegrityException($message);
	}
}
