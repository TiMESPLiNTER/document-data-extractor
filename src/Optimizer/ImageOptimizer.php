<?php

namespace DocumentDataExtractor\Optimizer;

/**
 * @author Pascal <dev@timesplinter.ch>
 * @copyright Copyright (c) 2017 by TiMESPLiNTER Webdevelopment
 */
interface ImageOptimizer
{
	public function getOptimizedImagePath(string $imagePath): string;
}