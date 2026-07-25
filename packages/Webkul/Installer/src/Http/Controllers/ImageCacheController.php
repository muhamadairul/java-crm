<?php

namespace Webkul\Installer\Http\Controllers;

use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Support\Facades\Cache;

class ImageCacheController
{
    /**
     * Cache template
     *
     * @var string
     */
    protected $template;

    /**
     * Logo
     *
     * @var string
     */
    const APP_LOGO = '';

    /**
     * Get HTTP response of template applied image file
     *
     * @param  string  $filename
     * @return Illuminate\Http\Response
     */
    public function getImage($filename)
    {
        try {
            $content = Cache::remember('java-crm-logo', 10080, function () {
                return $this->getImageFromUrl(self::APP_LOGO);
            });
        } catch (\Throwable $e) {
            $content = '';
        }

        return $this->buildResponse($content);
    }

    /**
     * Init from given URL
     *
     * @param  string  $url
     * @return string
     */
    public function getImageFromUrl($url)
    {
        if (empty($url) || ! is_string($url) || trim($url) === '') {
            throw new \Exception('Unable to init from given url (empty url).');
        }

        $domain = config('app.url');

        $options = [
            'http' => [
                'method'           => 'GET',
                'protocol_version' => 1.1, // force use HTTP 1.1 for service mesh environment with envoy
                'header'           => "Accept-language: en\r\n".
                "Domain: $domain\r\n".
                "User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36\r\n",
            ],
        ];

        $context = stream_context_create($options);

        if ($data = @file_get_contents($url, false, $context)) {
            return $data;
        }

        throw new \Exception(
            'Unable to init from given url ('.$url.').'
        );
    }

    /**
     * Builds HTTP response from given image data
     *
     * @param  string|null  $content
     * @return Illuminate\Http\Response
     */
    protected function buildResponse($content)
    {
        if (empty($content)) {
            return new IlluminateResponse('', 404);
        }

        /**
         * Define mime type
         */
        $mime = 'image/png';
        if (function_exists('finfo_open')) {
            $finfo = @finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo) {
                $detectedMime = @finfo_buffer($finfo, $content);
                if ($detectedMime) {
                    $mime = $detectedMime;
                }
            }
        }

        /**
         * Respond with 304 not modified if browser has the image cached
         */
        $eTag = md5($content);

        $notModified = isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == $eTag;

        $contentLength = strlen($content);
        $responseContent = $notModified ? null : $content;
        $statusCode = $notModified ? 304 : 200;

        /**
         * Return http response
         */
        return new IlluminateResponse($responseContent, $statusCode, [
            'Content-Type'   => $mime,
            'Cache-Control'  => 'max-age=10080, public',
            'Content-Length' => $contentLength,
            'Etag'           => $eTag,
        ]);
    }
}
