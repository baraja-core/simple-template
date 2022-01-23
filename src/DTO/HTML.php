<?php

declare(strict_types=1);

namespace Baraja\SimpleTemplate\DTO;


final class HTML implements \Stringable
{
	public function __construct(
		private string $html,
	) {
	}


	public function __toString(): string
	{
		return $this->html;
	}
}
