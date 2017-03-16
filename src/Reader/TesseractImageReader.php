<?php

namespace DocumentDataExtractor\Reader;

/**
 * @author Pascal <dev@timesplinter.ch>
 * @copyright Copyright (c) 2017 by TiMESPLiNTER Webdevelopment
 */
class TesseractImageReader implements Reader
{

	const IMAGE_DPI = 300;

	/**
	 * @var string|null
	 */
	protected $binaryPath;

	/**
	 * TesseractImageHandler constructor.
	 * @param string $binaryPath
	 */
	public function __construct(string $binaryPath = null)
	{
		$this->binaryPath = $binaryPath;
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

		$tmpFilename = tempnam(sys_get_temp_dir(), 'DocumentDataExtractor');
		$tmpFilename = __DIR__.'/tmp.png';

		$image = new \Imagick($filename);

		$image->setImageUnits(\Imagick::RESOLUTION_PIXELSPERINCH);
		$image->setImageResolution(self::IMAGE_DPI, self::IMAGE_DPI);
		/*$image->brightnessContrastImage(25, 60);
		$image->quantizeImage(32, \Imagick::COLORSPACE_GRAY, 0, \Imagick::DITHERMETHOD_NO, false);*/

		//$image->setImageDepth(8);

		$image->quantizeImage(32, \Imagick::COLORSPACE_GRAY, 0, \Imagick::DITHERMETHOD_NO, false);
		$image->negateImage(false);
		$kernel = \ImagickKernel::fromBuiltIn(\Imagick::KERNEL_DISK, "6");
		$image->morphology(\Imagick::MORPHOLOGY_CLOSE, 1, $kernel);

		$image->setImageFormat('png');
		$image->writeImage($tmpFilename);

		# Prepare the image to be annotated
		$ocr = new \TesseractOCR($tmpFilename);

		if (null !== $this->binaryPath) {
			$ocr->executable($this->binaryPath);
		}

		$ocr->lang('deu');
		return $ocr->run();
	}
}