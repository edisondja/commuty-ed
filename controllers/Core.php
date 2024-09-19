<?php


use Symfony\Component\HttpClient\NativeHttpClient;
use Symfony\Component\HttpClient\Psr18Client;

require __DIR__ . "/../vendor/autoload.php";

Class Core{



public static function GetGrapth($url)

    {
        // Initialize new Psr\HttpClient implementation. This example uses Symfony's implementation from the symfony/http-client
        // package but you can use any implementation provided by your framework of choice.
        $client = new Psr18Client(new NativeHttpClient([ "headers" => [ "User-Agent" => "facebookexternalhit/1.1" ] ]));

        // Create a new crawler
        $crawler = new Fusonic\OpenGraph\Consumer($client, $client);

        // Crawl the desired URL and retrieve a Fusonic\OpenGraph\Object in response
        $object = $crawler->loadUrl($url);


        //print_r($object);
      
        // Images

        if(isset($object->images[0])){

            $image = $object->images[0];
            $data_og = [
                'image'=>$image->url,
                'title'=>$object->title,
                'description'=>$object->description,
                'url'=>$object->url,
                'website'=>$object->siteName

            ];

        }else{
           
                $data_og = [
                    'image'=>'',
                    'title'=>$object->title,
                    'description'=>$object->description,
                    'url'=>$object->url,
                    'website'=>$object->siteName
                ];
        }



        echo json_encode($data_og);

    }



}





//echo "Image[0] height: " . $image->height;       
//echo "Image[0] width: " . $image->width;     

//Core::GetGrapth('https://videosegg.com//playvideo.php?id=8379/Video-Chrissy-Chambers-LEAKED-Blowjob');
// Videos
