<?php
namespace xingrl\circle_task\gd;

class Curl
{
    public static $RequestMethodGet = 1;
    public static $RequestMethodPost = 2;

    public static $RequestParamArray = 1;
    public static $RequestParamJson = 2;

    public static $RequestSchemeHttp = 'http';
    public static $RequestSchemeHttps = 'https';

    public static $ResponseContentEncodingGzip = 'gzip';

    private $curl;

    /**
     * @var bool
     */
    private $debug;
    /**
     * @var array 日志
     */
    private $debugTrace;

    /**
     * @var string
     */
    private $requestScheme;
    /**
     * @var string
     */
    private $requestHost;
    /**
     * @var string
     */
    private $requestPath;
    /**
     * @var string
     */
    private $requestUrl;
    /**
     * @var array
     */
    private $requestHeader;
    /**
     * @var string
     */
    private $requestParamCategory;
    /**
     * @var array 请求参数 [[name=value]]
     */
    private $requestParam;
    /**
     * @var string
     */
    private $requestUserAgent;
    /**
     * @var string
     */
    private $requestCookie;
    /**
     * @var string
     */
    private $requestCookieFile;
    /**
     * @var int
     */
    private $requestMethod;
    /**
     * @var bool
     */
    private $requestGetResponseHeader;

    /**
     * @var string
     */
    private $responseHeader;
    /**
     * @var int
     */
    private $responseHeaderSize;
    /**
     * @var string
     */
    private $responseBody;

    public function __construct()
    {
        $this->setDebug( false );
        $this->setDebugTrace( [] );

        $this->setRequestScheme( self::$RequestSchemeHttps );
        $this->setRequestHost( '' );
        $this->setRequestPath( '' );
        $this->setRequestUrl( '' );
        $this->setRequestParamCategory( self::$RequestParamArray );
        $this->setRequestParam( [] );
        $this->setRequestHeader( [] );
        $this->setRequestUserAgent( '' );
        $this->setRequestCookie( '' );
        $this->setRequestCookieFile( '' );
        $this->setRequestMethod( self::$RequestMethodGet );
        $this->setRequestGetResponseHeader( true );
    }

    /**
     * @param bool $debug
     */
    public function setDebug( bool $debug )
    {
        $this->debug = $debug;
    }

    /**
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * @param string $trace
     */
    public function addDebugTrace( $trace )
    {
        $this->debugTrace[] = date( '[Y-m-d H:i:s]' );
        $this->debugTrace[] = is_string( $trace )? $trace: var_export( $trace, true );
        $this->debugTrace[] = PHP_EOL;
    }

    /**
     * @param array $debugTrace
     */
    public function setDebugTrace( array $debugTrace )
    {
        $this->debugTrace = $debugTrace;
    }

    /**
     * @return array
     */
    public function getDebugTrace(): array
    {
        return $this->debugTrace;
    }

    /**
     * @return string
     */
    public function getRequestScheme(): string
    {
        return $this->requestScheme;
    }

    /**
     * @param string $requestScheme
     */
    public function setRequestScheme( string $requestScheme )
    {
        $this->requestScheme = $requestScheme;
    }

    /**
     * @return string
     */
    public function getRequestHost(): string
    {
        return $this->requestHost;
    }

    /**
     * @param string $requestHost
     */
    public function setRequestHost( string $requestHost )
    {
        $this->requestHost = $requestHost;
    }

    /**
     * @return string
     */
    public function getRequestPath(): string
    {
        return ltrim( $this->requestPath, '/' );
    }

    /**
     * @param string $requestPath
     */
    public function setRequestPath( string $requestPath )
    {
        $this->requestPath = $requestPath;
    }

    /**
     * @return string
     */
    public function getRequestUrl(): string
    {
        if( !$this->requestUrl ){
            $this->requestUrl = sprintf( '%s://%s/%s', $this->getRequestScheme(), $this->getRequestHost(), $this->getRequestPath() );

            if( $this->getRequestMethod() == self::$RequestMethodGet ){
                if( $this->getRequestParam() ){
                    $this->requestUrl .= '?'.http_build_query( $this->getRequestParam() );
                }
            }
        }

        return $this->requestUrl;
    }

    /**
     * @param string $requestUrl
     */
    public function setRequestUrl( string $requestUrl )
    {
        $this->requestUrl = $requestUrl;
    }

    /**
     * @return array
     */
    public function getRequestHeader(): array
    {
        return $this->requestHeader;
    }

    public function addRequestHeader( $requestHeader )
    {
        if( !in_array( $requestHeader, $this->requestHeader ) ){
            $this->requestHeader[] = $requestHeader;
        }
    }

    /**
     * @param array $requestHeader
     */
    public function setRequestHeader( array $requestHeader )
    {
        $this->requestHeader = $requestHeader;
    }

    /**
     * @return string
     */
    public function getRequestParamCategory(): string
    {
        return $this->requestParamCategory;
    }

    /**
     * @param string $requestParamCategory
     */
    public function setRequestParamCategory( string $requestParamCategory )
    {
        $this->requestParamCategory = $requestParamCategory;
    }

    /**
     * @return array
     */
    public function getRequestParam(): array
    {
        return $this->requestParam;
    }

    /**
     * @param array $requestParam
     */
    public function setRequestParam( array $requestParam )
    {
        $this->requestParam = $requestParam;
    }

    /**
     * @return string
     */
    public function getRequestUserAgent(): string
    {
        return $this->requestUserAgent;
    }

    /**
     * @param string $requestUserAgent
     */
    public function setRequestUserAgent( string $requestUserAgent )
    {
        $this->requestUserAgent = $requestUserAgent;
    }

    /**
     * @return string
     */
    public function getRequestCookie(): string
    {
        return $this->requestCookie;
    }

    /**
     * @param string $requestCookie
     */
    public function setRequestCookie( string $requestCookie )
    {
        $this->requestCookie = $requestCookie;
    }

    /**
     * @return string
     */
    public function getRequestCookieFile(): string
    {
        return $this->requestCookieFile;
    }

    /**
     * @param string $requestCookieFile
     */
    public function setRequestCookieFile( string $requestCookieFile )
    {
        $this->requestCookieFile = $requestCookieFile;
    }

    /**
     * @return int
     */
    public function getRequestMethod(): int
    {
        return $this->requestMethod;
    }

    /**
     * @param int $requestMethod
     */
    public function setRequestMethod( int $requestMethod )
    {
        $this->requestMethod = $requestMethod;
    }

    /**
     * @return bool
     */
    public function isRequestGetResponseHeader(): bool
    {
        return $this->requestGetResponseHeader;
    }

    /**
     * @param bool $requestGetResponseHeader
     */
    public function setRequestGetResponseHeader( bool $requestGetResponseHeader )
    {
        $this->requestGetResponseHeader = $requestGetResponseHeader;
    }

    /**
     * @return string
     */
    public function getResponseHeader(): string
    {
        return $this->responseHeader;
    }

    /**
     * @param string $responseHeader
     */
    public function setResponseHeader( string $responseHeader )
    {
        $this->responseHeader = $responseHeader;
    }

    /**
     * @return int
     */
    public function getResponseHeaderSize(): int
    {
        return $this->responseHeaderSize;
    }

    /**
     * @param int $responseHeaderSize
     */
    public function setResponseHeaderSize( int $responseHeaderSize )
    {
        $this->responseHeaderSize = $responseHeaderSize;
    }

    /**
     * @param $contentEncoding
     *
     * @return string
     */
    public function getResponseBody( $contentEncoding = null ): string
    {
        if( $contentEncoding ){
            switch( $contentEncoding ){
                case 'gzip':
                    return gzdecode( $this->responseBody );
                    break;
            }
        }

        return $this->responseBody;
    }

    /**
     * @param string $responseBody
     */
    public function setResponseBody( string $responseBody )
    {
        $this->responseBody = $responseBody;
    }

    /**
     * @return bool
     */
    public function makeRequest()
    {
        $this->setResponseHeaderSize( 0 );
        $this->setResponseHeader( '' );
        $this->setResponseBody( '' );

        $this->curl = curl_init();

        curl_setopt( $this->curl, CURLOPT_URL, $this->getRequestUrl() );
        curl_setopt( $this->curl, CURLOPT_RETURNTRANSFER, 1 );

        if( $this->isRequestGetResponseHeader() ){
            curl_setopt( $this->curl, CURLOPT_HEADER, true );
        }

        switch( $this->getRequestMethod() ){
            case self::$RequestMethodPost:
                curl_setopt( $this->curl, CURLOPT_POST, true );

                if( $this->getRequestParam() ){

                    if( $this->getRequestParamCategory() == self::$RequestParamJson ){
                        curl_setopt( $this->curl, CURLOPT_POSTFIELDS, json_encode( $this->requestParam ) );
                    }
                    else{
                        curl_setopt( $this->curl, CURLOPT_POSTFIELDS, $this->requestParam );
                    }
                }

                break;
            case self::$RequestMethodGet:
                curl_setopt( $this->curl, CURLOPT_POST, false );
                break;
        }

        if( $this->getRequestHeader() ){
            curl_setopt( $this->curl, CURLOPT_HTTPHEADER, $this->getRequestHeader() );
        }

        if( $this->getRequestUserAgent() ){
            curl_setopt( $this->curl, CURLOPT_USERAGENT, $this->getRequestUserAgent() );
        }

        if( $this->getRequestCookie() ){
            curl_setopt( $this->curl, CURLOPT_COOKIE, $this->getRequestCookie() );
        }

        if( $this->getRequestCookieFile() ){
            curl_setopt( $this->curl, CURLOPT_COOKIEJAR, $this->getRequestCookieFile() );
            curl_setopt( $this->curl, CURLOPT_COOKIEFILE, $this->getRequestCookieFile() );
        }

        if( $this->getRequestScheme() == self::$RequestSchemeHttps ){ //https
            curl_setopt( $this->curl, CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $this->curl, CURLOPT_SSL_VERIFYHOST, false );
        }

        $responseData = curl_exec( $this->curl );

        if( $this->isDebug() ){
            $this->addDebugTrace( var_export( curl_getinfo( $this->curl ), true ) );
        }

        if( $this->isRequestGetResponseHeader() ){
            $responseHeaderSize = curl_getinfo( $this->curl, CURLINFO_HEADER_SIZE );
        }
        else{
            $responseHeaderSize = 0;
        }

        if( $error = curl_error( $this->curl ) ){

            if( $this->isDebug() ){
                $this->addDebugTrace( $error );
            }

            return false;
        }
        else{
            if( $this->isDebug() ){
                $this->addDebugTrace( $responseData );
            }
        }

        if( $this->isRequestGetResponseHeader() ){
            $this->setResponseHeader( substr( $responseData, 0, $responseHeaderSize ) );
            $this->setResponseBody( substr( $responseData, $responseHeaderSize ) );
        }
        else{
            $this->setResponseBody( $responseData );
        }

        return true;
    }

    public function getResponseHttpCode()
    {
        return curl_getinfo( $this->curl, CURLINFO_HTTP_CODE );
    }

    public static function getByHeader( $headerStr, $headerKey )
    {
        $headerKeyName = Str::convertToCamel( $headerKey );

        if( preg_match( '/'.$headerKey.': (?<'.$headerKeyName.'>.+)/', $headerStr, $matches ) !== false ){
            if( array_key_exists( $headerKeyName, $matches ) ){
                return trim( $matches[ $headerKeyName ] );
            }
        }

        return '';
    }

    public function __destruct()
    {
        curl_close( $this->curl );
    }

    /**
     * @param string $url
     * http://exaple.com
     * @param array $requestParams
     * [[ name => value ]]
     *
     * @return array
     */
    public static function httpPost( $url, $requestParams )
    {
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_HEADER, true );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $requestParams ) );

        if( stripos( $url, 'https://' ) === 0 ){ //https
            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
        }

        $responseData = curl_exec( $ch );
        $responseHeaderSize = curl_getinfo( $ch, CURLINFO_HEADER_SIZE );

        if( $error = curl_error( $ch ) ){
            echo $error, PHP_EOL;
        }

        curl_close( $ch );

        $response = [
            'header' => substr( $responseData, 0, $responseHeaderSize ),
            'body' => substr( $responseData, $responseHeaderSize ),
        ];

        return $response;
    }

    public static function httpGet( $baseUrl, $requestParams )
    {
        $url = $baseUrl.'?'.http_build_query( $requestParams );

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_HEADER, true );

        if( stripos( $url, 'https://' ) === 0 ){ //https
            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
        }

        $responseData = curl_exec( $ch );
        $responseHeaderSize = curl_getinfo( $ch, CURLINFO_HEADER_SIZE );

        if( $error = curl_error( $ch ) ){
            echo $error, PHP_EOL;
        }

        curl_close( $ch );

        $response = [
            'header' => substr( $responseData, 0, $responseHeaderSize ),
            'body' => substr( $responseData, $responseHeaderSize ),
        ];

        return $response;
    }
}