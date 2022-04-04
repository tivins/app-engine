<?php

namespace Tivins\AppEngine;

use Tivins\AppEngine\Cache\PublicCache;
use Tivins\AppEngine\Controllers\CacheController;
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
            'meta'    => $this->meta . "\n" . $this->getMetaData(),
            'scripts' => $this->scripts . $inlineJS,
            'lang'    => $this->lang,
        ]);

        $this->setStatus($status);
        $this->setBody($tpl);
        HTTP::sendResponse($this);
    }

    private function getMetaData(): string
    {
        $tpl = Engine::getTemplate('meta.html');
        $tpl->setVar('manifestURL', PublicCache::url(Env::getWebmanifestCachePath()));
        return $tpl;
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
