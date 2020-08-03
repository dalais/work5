<?php

namespace App\Components\Http;

use App\Components\Interfaces\RequestInterface;

/**
 * Class Request
 *
 * @property string $user
 * @property string $home
 * @property string $httpAcceptLanguage
 * @property string $httpAcceptEncoding
 * @property string $httpAccept
 * @property string $httpUserAgent
 * @property string $httpUpgradeInsecureRequests
 * @property string $httpDnt
 * @property string $httpCacheControl
 * @property string $httpConnection
 * @property string $httpHost
 * @property string $redirectStatus
 * @property string $serverName
 * @property string $serverPort
 * @property string $serverAddr
 * @property string $remotePort
 * @property string $remoteAddr
 * @property string $serverSoftware
 * @property string $gatewayInterface
 * @property string $requestScheme
 * @property string $serverProtocol
 * @property string $documentRoot
 * @property string $documentUri
 * @property string $requestUri
 * @property string $scriptName
 * @property string $contentLength
 * @property string $contentType
 * @property string $requestMethod
 * @property string $queryString
 * @property string $scriptFilename
 * @property string $fcgiRole
 * @property string $phpSelf
 * @property float $requestTimeFloat
 * @property int $requestTime
 *
 * @package App\Components\Http
 */
class Request implements RequestInterface
{
    public function __construct()
    {
        $this->boot();
    }

    private function boot()
    {
        foreach($_SERVER as $key => $value)
        {
            $keyArr = explode("_",mb_strtolower($key));
            $rKey = '';
            if (count($keyArr) == 1) {
                $rKey = $keyArr[0];
            }
            if (count($keyArr) > 1) {
                foreach ($keyArr as $k => $v) {
                    $rKey .= ($k == 0) ? $v : ucfirst(strtolower($v));
                }
            }
            $this->{$rKey} = $value;
        }
    }

    public function getBody()
    {
        if($this->requestMethod === "GET")
        {
            return;
        }

        if ($this->requestMethod == "POST")
        {

            $body = array();
            foreach($_POST as $key => $value)
            {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }

            return $body;
        }
        return;
    }
}