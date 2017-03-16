<?php

namespace DocumentDataExtractor\Analyzer;

/**
 * @author Pascal <dev@timesplinter.ch>
 * @copyright Copyright (c) 2017 by TiMESPLiNTER Webdevelopment
 */
class IBANAnalyzer implements Analyzer
{

	public function analyze(string $content)
	{
		$normalizedContent = str_replace(' ', '', $content);

		$matches = [];
		if (0 === preg_match_all('/[A-Z]{2}\d{2}[0-9A-Z]{1,30}/', $normalizedContent, $matches)) {
			return $matches;
		}

		$ibans = [];

		foreach ($matches as $match) {
			$iban = $match[0];

			if ($this->validateIBAN($iban)) {
				$ibans[] = $iban;
			}
		}

		return $ibans;
	}

	protected function validateIBAN($iban)
	{
		$charsToNo = array_flip(range('A', 'Z'));

		$check = preg_replace_callback(
			'/[A-Z]/',
			function ($m) use($charsToNo) {
				return $charsToNo[$m[0]]+10;
			},
			substr($iban, 4) . substr($iban, 0, 4)
		);

		return '1' === bcmod((string) $check, '97');
	}

	public function getName(): string
	{
		return 'iban';
	}
}