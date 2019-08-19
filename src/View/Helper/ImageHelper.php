<?php

namespace Adminaut\View\Helper;

use Adminaut\Entity\File;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use WideImage\WideImage;
use Zend\View\Helper\AbstractHelper;

/**
 * Class ImageHelper
 * @package Adminaut\View\Helper
 */
class ImageHelper extends AbstractHelper
{
    /**
     * @var Filesystem
     */
    private $privateFilesystem;

    /**
     * @var Filesystem
     */
    private $publicFilesystem;

    /**
     * ImageHelper constructor.
     * @param Filesystem $privateFilesystem
     * @param Filesystem $publicFilesystem
     */
    public function __construct(Filesystem $privateFilesystem, Filesystem $publicFilesystem)
    {
        $this->privateFilesystem = $privateFilesystem;
        $this->publicFilesystem = $publicFilesystem;
    }

    /**
     * @param File $image
     * @param int|string $width
     * @param int|string $height
     * @param string $mode
     * @param int|string $cropAreaX
     * @param int|string $cropAreaY
     * @param string $bg
     * @param int $alpha
     * @return string
     * @throws \Exception
     * todo: make params after $height in array?
     */
    public function __invoke(File $image, $width = 'auto', $height = 'auto', $mode = 'clip', $cropAreaX = 'center', $cropAreaY = 'center', $bg = 'ffffff', $alpha = 0)
    {
        /** @var Local $publicAdapter */
        $publicAdapter = $this->publicFilesystem->getAdapter();

        /** @var string $sourcePath */
        $sourcePath = $image->getSavePath();

        /** @var string $sourceExtension */
        $sourceExtension = $image->getFileExtension();

        if ($width == 'auto' && $height == 'auto') {
            $resultPath = $sourcePath . '.' . $sourceExtension;
        } else if ($mode == 'clip' && ($width == 'auto' || $height == 'auto')) {
            if ($width != 'auto') {
                $resultPath = $sourcePath . '-' . $width . '-auto.' . $sourceExtension;
            } else {
                $resultPath = $sourcePath . '-auto-' . $height . '.' . $sourceExtension;
            }
        } else {
            $hash = md5($mode . '&' . $cropAreaX . '&' . $cropAreaY . '&' . $bg . '&' . $alpha);
            $resultPath = $sourcePath . '-' . $width . '-' . $height . '-' . $hash . '.' . $sourceExtension;
        }

        if (!$this->publicFilesystem->has($resultPath)) {
            /** @var Local $privateAdapter */
            $privateAdapter = $this->privateFilesystem->getAdapter();
            $fullPath = realpath($privateAdapter->applyPathPrefix($sourcePath));

            if($this->privateFilesystem->getMimetype($sourcePath) === 'image/svg+xml') {
                $resultPath = $sourcePath . '.svg';

                try {
                    $original = $this->privateFilesystem->read($sourcePath);
                    $this->publicFilesystem->write($resultPath, $original);
                } catch (\Exception $e) {
                    // TODO : log
                }
            } else {
                try {
                    $original = WideImage::load($fullPath);
                    $result = $original->copy();

                    if (function_exists('exif_read_data')) {
                        $exifData = @exif_read_data($fullPath);
                        $orientation = isset($exifData['Orientation']) ? $exifData['Orientation'] : 1;
                        $result = $result->correctExif($orientation);
                    }

                    if ($width !== 'auto' || $height !== 'auto') {
                        switch ($mode) {
                            case 'scale':
                                $_w = $width == 'auto' ? null : $width;
                                $_h = $height == 'auto' ? null : $height;

                                $result = $result->resize($_w, $_h, 'fill');
                                break;

                            case 'crop':
                                $_w = $width == 'auto' ? '100%' : $width;
                                $_h = $height == 'auto' ? '100%' : $height;

                                $_resizeW = $width == 'auto' ? null : $width;
                                $_resizeH = $height == 'auto' ? null : $height;

                                $allowedCropAreasX = ['left', 'center', 'right'];
                                $allowedCropAreasY = ['top', 'center', 'middle', 'bottom'];
                                $_cax = in_array($cropAreaX, $allowedCropAreasX) || is_integer($cropAreaX) ? $cropAreaX : 'center';
                                $_cay = in_array($cropAreaY, $allowedCropAreasY) || is_integer($cropAreaY) ? $cropAreaY : 'center';

                                $result = $result->resize($_w, $_h, 'outside')->crop($_cax, $_cay, $_w, $_h);
                                break;

                            case 'fill':
                                $_w = $width == 'auto' ? null : $width;
                                $_h = $height == 'auto' ? null : $height;

                                $_cw = $width == 'auto' ? '100%' : $width;
                                $_ch = $height == 'auto' ? '100%' : $height;
                                $_bg = str_replace('#', '', $bg);
                                list($r, $g, $b) = sscanf($_bg, "%02x%02x%02x");
                                $_bg = $original->allocateColorAlpha($r, $g, $b, $alpha);

                                $result = $result->resize($_w, $_h)->resizeCanvas($_cw, $_ch, 'center', 'center', $_bg);
                                break;

                            default:
                                $_w = $width == 'auto' ? null : $width;
                                $_h = $height == 'auto' ? null : $height;

                                $result = $result->resize($_w, $_h);
                                break;
                        }
                    }

                    $this->publicFilesystem->write($resultPath, $result->asString($sourceExtension));
                } catch (\Exception $e) {
                    // TODO : log
                }
            }
        }

        $publicPath = str_replace(realpath($_SERVER['DOCUMENT_ROOT']), '', realpath($publicAdapter->applyPathPrefix('/')));

        // fix windows directory separators
        $publicPath = str_replace('\\', '/', $publicPath);

        // remove / from string beginning
        $publicPath = ltrim($publicPath, '/');

        return '/' . $publicPath . '/' . $resultPath;
    }
}
