<?php

namespace Tivins\AppEngine;

class HTMLPage
{
    public string $file     = 'html.html';
    public string $title    = '';
    public string $lang     = 'en';
    public string $meta     = '';
    public string $styles   = '';
    public string $scripts  = '';
    public string $inlineJS = '';

    public function deliver($pageTpl): never
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
        header('Content-Type: text/html; charset=utf-8');
        echo $tpl;
        exit;
    }

    public function addCSS(string $url): static
    {
        $this->styles .= '<link rel="stylesheet" type="text/css" href="' . $url . '">';
        return $this;
    }

    public function addJS(string $url): static
    {
        $this->scripts .= '<script src="' . $url . '"></script>';
        return $this;
    }

    public function getMetaFromFiles(): string
    {
        $root = AppSettings::getInstance()->getRootDir();
        $meta = '';
        if (file_exists($root.'/public/apple-touch-icon.png')) {
            $meta .= '  <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">'."\n";
        }
        if (file_exists($root.'/public/favicon-32x32.png')) {
            $meta .= '  <link rel="image/png" sizes="32x32" href="/favicon-32x32.png">'."\n";
        }
        if (file_exists($root.'/public/favicon-16x16.png')) {
            $meta .= '  <link rel="image/png" sizes="16x16" href="/favicon-32x32.png">'."\n";
        }
        if (file_exists($root.'/public/site.webmanifest')) {
            $meta .= '  <link rel="manifest" href="/site.webmanifest">'."\n";
        }
        return $meta;
    }
}
