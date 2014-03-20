<?php

$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);
$fullpath = "http://uncyclopedia.wikia.com/" . $path;
$content = file_get_contents($fullpath);

$inject_js_css = '<link type="text/css" rel="stylesheet" href="/ingsoc_assets/css/main.css" /><script type="text/javascript" src="/ingsoc_assets/js/main.js"></script>';

$content = preg_replace( '/<\/head>/i', $inject_js_css . '</head>', $content );
$content = preg_replace('/uncyclopedia\.wikia\.com\//i', 'newspeaklopedia.com/', $content );

$words_file = "./words.json";
$words_string = file_get_contents( $words_file );
$words = json_decode( $words_string, true );

foreach ($words['censored'] as $value) {
   	$content = preg_replace( "/(>[^<]*[^a-zA-Z]?)($value)([^a-zA-Z]+)/i", '$1<span class="censored">$2</span>$3', $content );
}

foreach ($words['translations'] as $key => $value) {
	
	foreach ($value as $word) {
		$word = preg_replace( "/\./", "\.", $word);
   		$content = preg_replace( "/(>[^<]*[^a-zA-Z]?)($word)([^a-zA-Z]+)/i", '$1<span data-val="'.$word.'"class="newspeak-translation">'.$key.'</span>$3', $content );
   	}
}

echo $content;
?>