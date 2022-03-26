<?php

namespace Tivins\AppEngine;

use Tivins\Core\Http\HTTP;
use Tivins\Core\Http\Response;
use Tivins\Core\Http\Status;
use Tivins\Core\StringUtil;

class HTMLPage extends Response
{
    public string $file     = 'html.html';
    public string $title    = '';
    public string $lang     = 'en';
    public string $meta     = '';
    public string $styles   = '';
    public string $scripts  = '';
    public string $inlineJS = '';

    public function setTitle(string $value): static
    {
        $this->title = $value;
        return $this;
    }

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

        $this->setStatus($status);
        $this->setBody($tpl);
        HTTP::sendResponse($this);
    }

    private function getMetaFromFiles(): string
    {
        $rootPublic = Env::getPath('/public');
        $meta       = '';
        if (file_exists($rootPublic . '/apple-touch-icon.png')) {
            $meta .= '  <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">' . "\n";
        }
        if (file_exists($rootPublic . '/favicon-32x32.png')) {
            $meta .= '  <link rel="image/png" sizes="32x32" href="/favicon-32x32.png">' . "\n";
        }
        if (file_exists($rootPublic . '/favicon-16x16.png')) {
            $meta .= '  <link rel="image/png" sizes="16x16" href="/favicon-16x16.png">' . "\n";
        }

        if (file_exists($rootPublic . '/assets/meta/favicon-180x180.png')) {
            $meta .= '  <link rel="apple-touch-icon" sizes="180x180" href="/assets/meta/favicon-180x180.png">' . "\n";
        }
        if (file_exists($rootPublic . '/assets/meta/favicon-16x16.png')) {
            // $meta .= '  <link rel="image/png" sizes="16x16" href="/assets/meta/favicon-16x16.png">' . "\n";
        }
        if (file_exists($rootPublic . '/assets/meta/favicon-32x32.png')) {
            // $meta .= '  <link rel="image/png" sizes="32x32" href="/assets/meta/favicon-32x32.png">' . "\n";
            $meta .= '  <link type="image/png" rel="icon" href="/assets/meta/favicon-32x32.png">' . "\n";
        }
        if (file_exists($rootPublic . '/assets/meta/site.webmanifest')) {
            $meta .= '  <link rel="manifest" href="/assets/meta/site.webmanifest">' . "\n";
        }
        $meta .= '  <meta property="og:url" content="' . Engine::getAbsoluteURL() . '">' . "\n";
        $meta .= '  <meta property="og:site_name" content="' . StringUtil::html(AppData::settings()->getTitle()) . '">';
        // <meta property="og:title" content="Build software better, together">';
        return $meta;
    }

    /**
     * @param string ...$urls
     * @return $this
     * @see addJS()
     */
    public function addCSS(string ...$urls): static
    {
        foreach ($urls as $url) {
            $this->styles .= '<link rel="stylesheet" type="text/css" href="' . StringUtil::html($url) . '">' . "\n";
        }
        return $this;
    }

    /**
     * Add one or more URL to load as script.
     * @param string ...$urls
     * @return $this
     * @see addCSS()
     */
    public function addJS(string ...$urls): static
    {
        foreach ($urls as $url) {
            $this->scripts .= '<script src="' . StringUtil::html($url) . '"></script>' . "\n";
        }
        return $this;
    }

}
