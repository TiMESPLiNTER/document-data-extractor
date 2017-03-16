<?php

namespace DocumentDataExtractor\Analyzer;

/**
 * @author Pascal <dev@timesplinter.ch>
 * @copyright Copyright (c) 2017 by TiMESPLiNTER Webdevelopment
 */
class AnalyzerManager
{

	/**
	 * @var array|Analyzer[]
	 */
	protected $analyzers = [];

	public function addAnalyzer(Analyzer $analyzer): self
	{
		$this->analyzers[] = $analyzer;
		return $this;
	}

	public function analyze(string $text)
	{
		$data = [];

		foreach ($this->analyzers as $analyzer) {
			$data[$analyzer->getName()] = $analyzer->analyze($text);
		}

		return $data;
	}
}