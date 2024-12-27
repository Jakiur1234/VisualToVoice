<?php
$lang = "en";
$converted = "";
// dd(urlencode($text));
$mp3 = 'http://translate.google.com/translate_tts?ie=UTF-8&q='. urlencode($text) .'&tl='. $lang .'&total=1&idx=0&textlen=5&prev=input';
?>

<a href="<?= $mp3 ?>" target="_blank">Download Speech</a>

