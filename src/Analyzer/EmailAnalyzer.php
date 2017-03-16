<?php


namespace DocumentDataExtractor\Analyzer;


/**
 * @author Pascal <dev@timesplinter.ch>
 * @copyright Copyright (c) 2017 by TiMESPLiNTER Webdevelopment
 */
class EmailAnalyzer implements Analyzer
{

	public function analyze(string $content)
	{
		$emails = [];

		$patterns = [
			'/[\w.!#$%&’*+\/=?^_`{|}~-]+@[\w-]+(?:\.[a-z0-9-]+)*/i',
		];

		foreach ($patterns as $pattern) {
			$matches = [];
			if (0 === preg_match_all($pattern, $content, $matches)) {
				continue;
			}

			foreach ($matches as $match) {
				$email = $match[0];

				if (false === in_array($email, $emails, true)) {
					$emails[] = $email;
				}
			}
		}

		return $emails;
	}

	public function getName(): string
	{
		return 'email';
	}
}
