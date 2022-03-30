<?php
namespace ext;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
class Oos{
    public static $imgHost="http://oos.mmlang.com/upload.php";  
    public static function Upload($from){
        
        $name=str_replace(public_path()."/","",$from);
        $file = curl_file_create($from, 'image/jpeg',$name);
        $client = new Client();
         
        $response = $client->request('POST',self::$imgHost, [
            'multipart' => [
                [
                    'name'     => 'filename',
                    'contents' => $name
                ],
                [
                    'name'     => "upimg", 
                    'filename' => $name,
                    'contents' => Psr7\Utils::tryFopen($from, 'r')
                ]
            ]
        ]);
       
        return true;

    }
}