<?php

declare(strict_types=1);

namespace Baraja\SimpleTemplate;


use Baraja\SimpleTemplate\DTO\VariableMetaInfo;

final class Engine
{
	private ?Parser $parser = null;


	public function renderTemplate(string $template, TemplateData $data): string
	{
		$template = trim($template);
		if ($template === '') {
			return '';
		}
		$this->validateTemplate($template, $data);
		$availableVariables = $this->parseAvailableVariables($data);

		$template = str_replace(["\r\n", "\r"], "\n", trim($template));
		$template = (string) preg_replace_callback(
			'/\{\{\s*([a-zA-Z0-9]+)\s*}}/',
			static function (array $match) use ($availableVariables): string
			{
				$variableName = $match[1] ?? '';
				$variable = $availableVariables[$variableName] ?? null;
				if ($variable === null) {
					throw new \LogicException(sprintf('Variable "%s" is not defined.', $variableName));
				}

				return $variable->renderRealValue();
			},
			$template,
		);
		$template = str_replace("\n\n", "<br>\n", trim($template));

		return $template;
	}


	/**
	 * Verifies that the passed template is valid and includes all variables.
	 * If it does, the method returns nothing. If the template is invalid, an exception is thrown with a reason.
	 * A data inconsistency or a logical error that only shows up at runtime is also considered an invalid template.
	 *
	 * @throws InvalidTemplateIntegrityException
	 */
	public function validateTemplate(string $template, TemplateData $data): void
	{
		$template = trim($template);
		if ($template === '') {
			return;
		}
		$availableVariables = $this->parseAvailableVariables($data);
		foreach ($this->parseUsedVariables($template) as $variable) {
			if (isset($availableVariables[$variable]) === false) {
				throw new InvalidTemplateIntegrityException(sprintf('Variable "%s" is not available.', $variable));
			}
		}
	}


	/**
	 * @return array<string, VariableMetaInfo>
	 * @throws InvalidTemplateIntegrityException
	 */
	public function parseAvailableVariables(TemplateData $data): array
	{
		return $this->getParser()->parseAvailableVariables($data);
	}


	/**
	 * @return array<int, string>
	 * @throws InvalidTemplateIntegrityException
	 */
	public function parseUsedVariables(string $template): array
	{
		return $this->getParser()->parseUsedVariables($template);
	}


	public function getParser(): Parser
	{
		if ($this->parser === null) {
			$this->parser = new Parser;
		}

		return $this->parser;
	}
}
