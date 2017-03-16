<?php

namespace DocumentDataExtractor\Reader;

/**
 * @author Pascal <dev@timesplinter.ch>
 * @copyright Copyright (c) 2017 by TiMESPLiNTER Webdevelopment
 */
class DelegateReader implements Reader
{
	/**
	 * @var Reader[]
	 */
	protected $readers = [];

	public function addReader(Reader $handler): self
	{
		$this->readers[] = $handler;

		return $this;
	}

	public function read(string $fileName): string
	{
		$mimeType = mime_content_type($fileName);

		if (null === $handler = $this->findHandlerForMimeType($mimeType)) {
			throw new \LogicException(
				sprintf('No handler found for file %s with mime-type %s', $fileName, $mimeType)
			);
		}

		return $handler->read($fileName);
	}

	/**
	 * @param string $mimeType
	 * @return Reader|null
	 */
	protected function findHandlerForMimeType(string $mimeType)
	{
		foreach ($this->readers as $handler) {
			if(true === $handler->supports($mimeType)) {
				return $handler;
			}
		}

		return null;
	}

	public function supports(string $mimeType): bool
	{
		return null !== $this->findHandlerForMimeType($mimeType);
	}
}