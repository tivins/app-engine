<?php

namespace Tivins\AppEngine;

use Tivins\Core\Http\Status;
use Tivins\Core\StringUtil;

class HTMLPage
{
    public string $file     = 'html.html';
    public string $title    = '';
    public string $lang     = 'en';
    public string $meta     = '';
    public string $styles   = '';
    public string $scripts  = '';
    public string $inlineJS = '';

    public function deliver($pageTpl, Status $status = Status::OK): never
    {
        $inlineJS = $this->inlineJS ? '<script>/*<![CDATA[*/' . $this->inlineJS . '/*]]>*/</script>' : '';
        $tpl      = Engine::getTemplate($this->file);
        $tpl->setVars([
            'body'    => $pageTpl,
            'title'   => $this->title,
            'styles'  => $this->styles,
            'meta'    => $this->meta . "\n" . $this->getMetaFromFiles(),
            'scripts' => $this->scripts . $inlineJS,
            'lang'    => $this->lang,
        ]);

        http_response_code($status->value);
        header('Content-Type: text/html; charset=utf-8');
        echo $tpl;
        exit;
    }

    public function getMetaFromFiles(): string
    {
        $rootPublic = AppSettings::getInstance()->getRootDir() . '/public';
        $meta       = '';
        if (file_exists($rootPublic . '/apple-touch-icon.png')) {
            $meta .= '  <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">' . "\n";
        }
        if (file_exists($rootPublic . '/favicon-32x32.png')) {
            $meta .= '  <link rel="image/png" sizes="32x32" href="/favicon-32x32.png">' . "\n";
        }
        if (file_exists($rootPublic . '/favicon-16x16.png')) {
            $meta .= '  <link rel="image/png" sizes="16x16" href="/favicon-32x32.png">' . "\n";
        }
        if (file_exists($rootPublic . '/site.webmanifest')) {
            $meta .= '  <link rel="manifest" href="/site.webmanifest">' . "\n";
        }
        return $meta;
    }

    public function addCSS(string $url): static
    {
        $this->styles .= '<link rel="stylesheet" type="text/css" href="' . StringUtil::html($url) . '">';
        return $this;
    }

    /**
     * Add one or more URL to load as script.
     * @param string ...$urls
     * @return $this
     */
    public function addJS(string ...$urls): static
    {
        foreach ($urls as $url) {
            $this->scripts .= '<script src="' . StringUtil::html($url) . '"></script>';
        }
        return $this;
    }
}
