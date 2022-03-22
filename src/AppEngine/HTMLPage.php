<?php

namespace Tivins\AppEngine;

class HTMLPage
{
    public string $file    = 'html.html';
    public string $title   = '';
    public string $lang    = 'en';
    public string $meta    = '';
    public string $styles  = '';
    public string $scripts = '';

    public function deliver($pageTpl): never
    {
        $tpl = Engine::getTemplate($this->file);
        $tpl->setVars([
            'body'    => $pageTpl,
            'title'   => $this->title,
            'styles'  => $this->styles,
            'meta'    => $this->meta,
            'scripts' => $this->scripts,
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
}
