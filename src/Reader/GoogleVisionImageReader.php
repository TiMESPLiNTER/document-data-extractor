<?php

namespace DocumentDataExtractor\Reader;

use Google\Cloud\Vision\VisionClient;
use DocumentDataExtractor\Optimizer\ImageOptimizer;

/**
 * @author Pascal <dev@timesplinter.ch>
 * @copyright Copyright (c) 2017 by TiMESPLiNTER Webdevelopment
 */
class GoogleVisionImageReader implements Reader
{

	/**
	 * @var VisionClient
	 */
	protected $vision;

	/**
	 * @var ImageOptimizer|null
	 */
	protected $imageOptimizer;

	/**
	 * ImageHandler constructor.
	 * @param string $credentialFile
	 * @param ImageOptimizer $imageOptimizer
	 */
	public function __construct(string $credentialFile, ImageOptimizer $imageOptimizer = null)
	{
		putenv('GOOGLE_APPLICATION_CREDENTIALS='.$credentialFile);

		$credentials = json_decode(file_get_contents($credentialFile), true);

		$this->imageOptimizer = $imageOptimizer;
		$this->vision = new VisionClient([
			'projectId' => $credentials['project_id']
		]);
	}

	public function supports(string $mimeType): bool
	{
		return in_array($mimeType, [
			'image/jpeg',
			'image/gif'
		]);
	}

	public function read(string $filename): string
	{

		if (null !== $this->imageOptimizer) {
			$filename = $this->imageOptimizer->getOptimizedImagePath($filename);
		}

		# Prepare the image to be annotated
		$image = $this->vision->image(fopen($filename, 'r'), [
			'TEXT_DETECTION',
			'DOCUMENT_TEXT_DETECTION'
		]);

		# Performs label detection on the image file
		return $this->vision->annotate($image)->fullText()->text();
	}
}
