<?php
namespace xingrl\circle_task\gd;

require_once __DIR__.'/init.php';

class Egg
{
    private $curl;
    private $file;

    public function __construct( $index, $user )
    {
        $this->file = realpath( __DIR__.'/../../' ).'/var/run/gd';
        if( !file_exists($this->file) ){
            mkdir($this->file, 0777, true);
        }

        $this->curl = new Curl();

        $this->curl->setRequestScheme( Curl::$RequestSchemeHttps );
        $this->curl->setRequestHost( Config::get('url') );
        $this->curl->setRequestUserAgent( $user[ 'userAgent' ] );
        $this->curl->setRequestCookie( $user[ 'cookie' ] );
        $this->curl->setRequestCookieFile( sprintf( $this->file.'/cookie-%d.txt', $index ) );
        $this->curl->setRequestGetResponseHeader( true );
    }

    public function execute()
    {
        $this->curl->setDebug( true );

        if( $this->curlEggPage() ){
            $this->curlEgg();
        }

        file_put_contents( $this->file.'/log.txt', implode( PHP_EOL, $this->curl->getDebugTrace() ), FILE_APPEND );
    }

    /**
     * @return bool
     */
    private function curlEggPage()
    {
        $this->curl->setRequestPath( Config::get('page-index') );
        $this->curl->setRequestMethod( Curl::$RequestMethodGet );

        $result = $this->curl->makeRequest();

        if( $result ){
            echo $this->curl->getResponseHeader();
            echo $this->curl->getResponseBody();
        }

        return $result;
    }

    private function curlEgg()
    {
        $this->curl->setRequestUrl( '' );
        $this->curl->setRequestPath( Config::get('page-game') );
        $this->curl->setRequestMethod( Curl::$RequestMethodPost );

        $result = $this->curl->makeRequest();

        if( $result ){
            echo $this->curl->getResponseHeader();
            echo $this->curl->getResponseBody();

            # send mail
            $mail = new MailBy163();
            $mail->send( Config::get('admin'), date( '[H:i]' ).'砸蛋成功！', json_decode( $this->curl->getResponseBody(), true ) );
        }
    }
}


foreach( Config::get( 'users' ) as $k => $user ){
    sleep( rand( 1, 100 ) );

    $obj = new Egg( $k, $user );
    $obj->execute();
}