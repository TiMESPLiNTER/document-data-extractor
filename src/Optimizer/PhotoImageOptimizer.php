<?php

namespace DocumentDataExtractor\Optimizer;

/**
 * @author Pascal <dev@timesplinter.ch>
 * @copyright Copyright (c) 2017 by TiMESPLiNTER Webdevelopment
 */
class PhotoImageOptimizer implements ImageOptimizer
{

	const SCALE = 3000;

	public function getOptimizedImagePath(string $imagePath): string
	{
		$image = new \Imagick($imagePath);
		$orientation = $image->getImageOrientation();

		switch($orientation) {
			case 6: // rotate 90 degrees CW
				$image->rotateimage("#FFF", 90);
				break;

			case 8: // rotate 90 degrees CCW
				$image->rotateimage("#FFF", -90);
				break;
		}

		$tmpFilename = tempnam(sys_get_temp_dir(), 'DocumentDataExtractor');

		$image->setImageInterpolateMethod(\Imagick::INTERPOLATE_AVERAGE);
		$image->scaleImage(self::SCALE, self::SCALE, true);
		$image->quantizeImage(128, \Imagick::COLORSPACE_GRAY, 0, \Imagick::DITHERMETHOD_UNDEFINED, false);
		$image->setImageDepth(8);

		$black = 10;
		$white = 90;
		$gamma = 0.5;

		$image->normalizeImage();
		$quantum = $image->getQuantum();
		$image->levelImage($black/100, $gamma, $quantum * $white / 100);
		$image->blurImage(0.5, 0.5);

		$image->setImageFormat('png');
		$image->writeImage($tmpFilename);

		return $tmpFilename;
	}
}