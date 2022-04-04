<?php

namespace Tivins\AppEngine;

use Imagick;
use ImagickDraw;
use ImagickDrawException;
use ImagickException;
use ImagickPixel;
use ImagickPixelException;
use Tivins\AppEngine\Cache\PublicCache;
use Tivins\Core\System\FileSys;

class Meta
{
    public static function build(bool $rebuildAll = false): void
    {
        $settings = AppData::settings();
        $icon     = self::buildIcon();
        FileSys::mkdir(PublicCache::path(Env::DIR_PUBLIC_CACHE_META));

        $meta  = [
            'name'             => $settings->getTitle(),
            'short_name'       => $settings->getShortTitle(),
            'theme_color'      => $settings->getThemeColor(),
            'background_color' => $settings->getBackgroundColor(),
            'display'          => 'standalone',
            'icons'            => [
            ],
        ];
        $sizes = [512, 192, 180, 64, 32, 16];
        foreach ($sizes as $size) {
            $url  = PublicCache::url(Env::DIR_PUBLIC_CACHE_META.'/'. self::getIconName($size));
            $path = PublicCache::path(Env::DIR_PUBLIC_CACHE_META.'/'. self::getIconName($size));

            if ($rebuildAll || !file_exists($path)) {
                self::generateThumbs($icon, $size, $path);
            }
            $meta['icons'][] = [
                'src'   => $url,
                'sizes' => sprintf('%dx%d', $size, $size),
                'type'  => 'image/png',
            ];
        }

        $path = PublicCache::path(Env::getWebmanifestCachePath());
        if ($rebuildAll || !file_exists($path)) {
            if (!FileSys::writeFile($path, json_encode($meta))) {
                /* @todo manage error */
            }
        }
    }

    /**
     * @throws ImagickDrawException
     * @throws ImagickPixelException
     * @throws ImagickException
     */
    public static function buildIcon(): Imagick
    {
        $settings = AppData::settings();

        $imagick = new Imagick();

        $draw = new ImagickDraw();
        $draw->setFillColor(new ImagickPixel($settings->getPrimaryColor()));
        $draw->roundRectangle(0, 0, 512, 512, 51, 51);

        $text    = AppData::settings()->getIconLetter();
        $drawTxt = new ImagickDraw();
        $drawTxt->setTextKerning(-50);
        $drawTxt->setFont($settings->getIconFontPath());
        $drawTxt->setFontSize($settings->getIconFontSize());
        $drawTxt->setFontWeight(600);
        $drawTxt->setTextAlignment(Imagick::ALIGN_CENTER);
        $metrics = $imagick->queryFontMetrics($draw, $text);
        $drawTxt->setFillColor(new ImagickPixel('#fff'));
        $drawTxt->annotation((512 - $metrics['textWidth']) / 2, 250 + (512 - 300) / 2, $text);

        $imagick->newImage(512, 512, 'transparent');
        $imagick->drawImage($draw);
        $imagick->drawImage($drawTxt);
        $imagick->setImageFormat('png');
        return $imagick;
    }

    public static function generateThumbs($imagick, $size, $filePath)
    {
        $i32 = clone $imagick;
        $i32->cropThumbnailImage($size, $size);
        $i32->writeImage($filePath);
    }

    public static function getIconName(int $size): string
    {
        return "favicon-{$size}x$size.png";
    }

}