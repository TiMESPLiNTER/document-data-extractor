<?php


namespace DocumentDataExtractor\Analyzer;


/**
 * @author Pascal <dev@timesplinter.ch>
 * @copyright Copyright (c) 2017 by TiMESPLiNTER Webdevelopment
 */
class DateAnalyzer implements Analyzer
{

	const MONTHS_GERMAN = [
		1 => 'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August',
		'September', 'Oktober', 'November', 'Dezember'
	];

	const MONTHS_ENGLISH = [
		1 => 'January', 'February', 'March', 'April', 'Mai', 'June', 'July', 'August',
		'September', 'October', 'November', 'December'
	];

	public function analyze(string $content)
	{
		$dates = [];
		$monthMap = array_combine(self::MONTHS_GERMAN, self::MONTHS_ENGLISH);

		$patterns = [
			'/(?:January|Feburary|March|April|May|June)\s+\d{1,2},?\s+\d{2,4}/' => function (array $matches) {
				return $matches[0];
			},
			'/\d{1,2}\.\s+(' . implode('|', self::MONTHS_GERMAN) . ')(?:\s+\d{4})?/' => function (array $match) use ($monthMap) {
				return strtr($match[0], $monthMap);
			},
			'/\d{4}-\d{2}-\d{2}/' => function (array $matches) { return $matches[0]; },
			'/\d{1,2}\.\d{1,2}\.\d{2,4}/' => function (array $matches) { return $matches[0]; },
		];

		foreach ($patterns as $pattern => $transformer) {
			$matches = [];
			if (0 === preg_match_all($pattern, $content, $matches, PREG_SET_ORDER)) {
				continue;
			}

			foreach ($matches as $possibleDate) {
				try {
					$date = new \DateTime($transformer($possibleDate));
				} catch (\Exception $e) {
					continue;
				}

				if (true === in_array($date, $dates, true)) {
					continue;
				}

				$dates[] = $date;
			}
		}

		return $dates;
	}

	public function getName(): string
	{
		return 'date';
	}
}