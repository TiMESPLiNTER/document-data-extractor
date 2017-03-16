<?php

namespace DocumentDataExtractor\Reader;

/**
 * @author Pascal <dev@timesplinter.ch>
 * @copyright Copyright (c) 2017 by TiMESPLiNTER Webdevelopment
 */
interface Reader
{
	public function supports(string $filename): bool;

	public function read(string $filename): string;
}