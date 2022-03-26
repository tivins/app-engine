<?php

use Tivins\AppEngine\Boot;
use Tivins\AppEngine\Env;
use Tivins\Core\System\FileSys;

require __dir__ . '/../vendor/autoload.php';
$opts = (new \Tivins\Core\OptionsArgs());
$args = Boot::init(__dir__ . '/..', $opts);

/**
 * @throws ImagickDrawException
 * @throws ImagickPixelException
 * @throws ImagickException
 */
function generateMeta()
{
    $settings = \Tivins\AppEngine\AppData::settings();

    FileSys::mkdir(Env::getPath('/public/assets/meta'));


    $meta = [
        'name'             => $settings->getTitle(),
        'short_name'       => $settings->getShortTitle(),
        'theme_color'      => $settings->getThemeColor(),
        'background_color' => $settings->getBackgroundColor(),
        'display'          => 'standalone',
        'icons'            => [],
    ];

    $imagick = new Imagick();

    $draw = new ImagickDraw();
    $draw->setFillColor(new ImagickPixel($settings->getPrimaryColor()));
    $draw->roundRectangle(0, 0, 512, 512, 51, 51);

    $text    = \Tivins\AppEngine\AppData::settings()->getIconLetter();
    $drawTxt = new ImagickDraw();
    $drawTxt->setTextKerning(-50);
    $drawTxt->setFont(__dir__ . '/Leckerli_One.ttf');
    $drawTxt->setFontSize(300);
    $drawTxt->setFontWeight(600);
    $drawTxt->setTextAlignment(Imagick::ALIGN_CENTER);
    $metrics = $imagick->queryFontMetrics($draw, $text);
    $drawTxt->setFillColor('#fff');
    $drawTxt->annotation((512 - $metrics['textWidth']) / 2, 250 + (512 - 300) / 2, $text);


    $imagick->newImage(512, 512, 'transparent');
    $imagick->drawImage($draw);
    $imagick->drawImage($drawTxt);
    $imagick->setImageFormat('png');

    $sizes = [512, 192, 180, 64, 32, 16];
    foreach ($sizes as $size) {
        $url = sprintf('/assets/meta/favicon-%dx%d.png', $size, $size);
        generateThumbs($imagick, $size, Env::getPath('/public' . $url));
        $meta['icons'][] = [
            'src'   => $url,
            'sizes' => sprintf('%dx%d', $size, $size),
            'type'  => 'image/png',
        ];
    }

    FileSys::writeFile(Env::getPath('/public/assets/meta/site.webmanifest'), json_encode($meta, JSON_PRETTY_PRINT));

}

function generateThumbs($imagick, $size, $filePath)
{
    $i32 = clone $imagick;
    $i32->cropThumbnailImage($size, $size);
    $i32->writeImage($filePath);
}

