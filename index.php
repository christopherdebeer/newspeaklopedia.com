<?php

    require_once("./lib/phpfastcache/phpfastcache.php");

    $cache = phpFastCache();
    $uri = $_SERVER['REQUEST_URI'];
    $path = parse_url($uri, PHP_URL_PATH);
    $content = $cache->get( $path );

    if ($content == null) {
        
        $fullpath = "http://uncyclopedia.wikia.com/" . $path;
        $content = file_get_contents($fullpath);
        $inject_head = '<meta property="og:description" content="Ministry of Truth approved knowledgebase for the education of Proles and Outer Party members. We already know what you are thinking." ><meta property="og:image" content="http://newspeaklopedia.com/ingsoc_assets/images/logo.png" /><meta property="fb:app_id" content="229994447193376" /><meta property="og:type" content="website" /><meta property="og:title" content="Newspeaklopedia" /> <link rel="stylesheet" type="text/css" href="/ingsoc_assets/css/main.css" /><script src="/ingsoc_assets/js/jquery.js" type="text/javascript"></script><script src="/ingsoc_assets/js/main.js" type="text/javascript"></script>';
        $content = preg_replace( '/<\/head>/i', $inject_head . 'INGSOC_CACHE</head>', $content );       
        $content = preg_replace('/uncyclopedia\.wikia\.com\//i', 'newspeaklopedia.com/', $content );
        $content = preg_replace('/"[^"]*ico"/i', '/ingsoc_assets/images/favicon.ico', $content );
        $content = preg_replace('/<title.*title>/i', '<title>Newspeaklopedia</title>', $content );
        $content = preg_replace('/<meta[^>]*>/i', '', $content );
        $content = preg_replace('/<link rel="(search|copyright|alternate|EditURI)[^>]*>/i', '', $content);
        $content = preg_replace('/<script[^>]*(google-analytics|quantserve).*script>/i', '', $content);

        $words_file = "./words.json";
        $words_string = file_get_contents( $words_file );
        $words = json_decode( $words_string, true );

        foreach ($words['censored'] as $value) {
            $content = preg_replace( "/(>[^<]*[^a-zA-Z]?)($value)([^a-zA-Z]+)/i", '$1<span class="censored">$2</span>$3', $content );
        }


        foreach ($words['translations'] as $key => $value) {
            
            foreach ($value as $word) {
                $word = preg_replace( "/\./", "\.", $word);
                $content = preg_replace( "/(>[^<]*[^a-zA-Z]?)($word)([^a-zA-Z]+)/i",'$1<span data-val="'.$word.'"class="newspeak-translation">'.$key.'</span>$3', $content );
            }
        }


        $cache->set( $path, $content , 600);

        $content = preg_replace('/INGSOC_CACHE/i', "<!-- not from cache (sorry) -->", $content );
        echo $content;

    } else {
        $content = preg_replace('/INGSOC_CACHE/i', "<!-- from cache :) -->", $content );
        echo $content;
    }


?>
