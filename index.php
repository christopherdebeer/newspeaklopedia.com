<?php




$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);

echo $path;
echo $uri;


$fullpath = "http://uncyclopedia.wikia.com/" . $path;
$content = file_get_contents($fullpath);


$content1 = preg_replace( '/<\/head>/i', '<style type="text/css">' . file_get_contents('./ingsoc_assets/css/main.css') . '</style><script type="text/javascript">' . file_get_contents('./ingsoc_assets/js/main.js') . '</script></head>', $content );

$pattern2 = '/uncyclopedia\.wikia\.com\//i';
$replacement2 = 'newspeak.christopherdebeer.com/';
$content2 = preg_replace($pattern2, $replacement2, $content1 );

$words_file = "./words.json";
$words_string = file_get_contents( $words_file );
$words = json_decode( $words_string, true );

$content_censor = $content2;

foreach ($words['censored'] as $value) {
   	$content_censor = preg_replace( "/(>[^<]*[^a-zA-Z]?)($value)([^a-zA-Z]+)/i", '$1<span class="censored">$2</span>$3', $content_censor );
}

$content_translate = $content_censor;
foreach ($words['translations'] as $key => $value) {
	
	foreach ($value as $word) {
		$word = preg_replace( "/\./", "\.", $word);
   		$content_translate = preg_replace( "/(>[^<]*[^a-zA-Z]?)($word)([^a-zA-Z]+)/i", '$1<span data-val="'.$word.'"class="newspeak-translation">'.$key.'</span>$3', $content_translate );
   	}
}

echo $content_translate;
?>