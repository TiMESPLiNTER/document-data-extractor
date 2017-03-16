<?php


namespace DocumentDataExtractor\Analyzer;


/**
 * @author Pascal <dev@timesplinter.ch>
 * @copyright Copyright (c) 2017 by TiMESPLiNTER Webdevelopment
 */
interface Analyzer
{
	public function analyze(string $content);

	public function getName(): string;
}