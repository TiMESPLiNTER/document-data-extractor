<?php

namespace DocumentDataExtractor\Analyzer;

/**
 * @author Pascal <dev@timesplinter.ch>
 * @copyright Copyright (c) 2017 by TiMESPLiNTER Webdevelopment
 */
class ESRAnalyzer implements Analyzer
{

	const ESR_PATTERN = '/([0-9ou]{13})>([0-9ou]+)\+([0-9ou]+)>?/i';

	const CHAR_TO_NUMBER_INTERPOLATION = ['u' => 0, 'o' => 0];

	public function analyze(string $content)
	{
		$normalizedContent = str_replace(' ', '', $content);

		$esrNumbers = [];
		$matches    = [];

		if (0 === preg_match_all(self::ESR_PATTERN, $normalizedContent, $matches, PREG_SET_ORDER)) {
			return $esrNumbers;
		}

		foreach ($matches as $match) {
			$esrAmountPart = strtr(strtolower($match[1]), self::CHAR_TO_NUMBER_INTERPOLATION);

			if (self::modulo10((int) substr($esrAmountPart, 0, 12)) !== (int) substr($esrAmountPart, -1, 1)) {
				continue;
			}

			$esrNumbers[] = [
				'esr' => self::interpolateString($match[0]),
				'amount' => ((int) substr(self::interpolateString($esrAmountPart), 2, 10)) * 0.01,
				'reference' => self::interpolateString($match[2]),
				'account' => self::interpolateString($match[3]),
			];
		}

		return $esrNumbers;
	}

	/**
	 * Interpolates some characters of a string to numbers
	 * @param string $str
	 * @return string
	 */
	protected static function interpolateString(string $str): string
	{
		return strtr(strtolower($str), self::CHAR_TO_NUMBER_INTERPOLATION);
	}

	/**
	 * Somehow PHP's bcmod() does not work in this case...
	 * @param int $number
	 * @return int
	 */
	protected static function modulo10(int $number): int
	{
		$numbers = [0, 9, 4, 6, 8, 2, 7, 1, 3, 5];
		$next = 0;

		for ($i = 0; $i < strlen($number); $i++) {
			$next = $numbers[($next + (int) substr($number, $i, 1)) % 10];
		}

		return (10 - $next) % 10;
	}

	public function getName(): string
	{
		return 'esr';
	}
}