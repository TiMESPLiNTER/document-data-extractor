<?php

namespace DocumentDataExtractor\Reader;

use Spatie\PdfToText\Pdf;

/**
 * @author Pascal <dev@timesplinter.ch>
 * @copyright Copyright (c) 2017 by TiMESPLiNTER Webdevelopment
 */
class PdfReader implements Reader
{

	/**
	 * @var string|null
	 */
	protected $binPath = null;

	/**
	 * PdfHandler constructor.
	 * @param null|string $binPath
	 */
	public function __construct(string $binPath = null)
	{
		$this->binPath = $binPath;
	}


	public function supports(string $mimeType): bool
	{
		return $mimeType === 'application/pdf';
	}

	public function read(string $filename): string
	{
		return Pdf::getText($filename, $this->binPath);
	}
}