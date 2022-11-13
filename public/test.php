<?php

require_once "../vendor/autoload.php";

use PHPHtmlParser\Dom;

$test = new Ofmtools\Test();

$test->test();

$file = file_get_contents("../debug/Zuschauer_2541457_104_31.xls");
$dom = new Dom;
$dom->loadStr($file);
$rows = $dom->getElementsByTag('tr')->toArray();
$rows = array_map(function(PHPHtmlParser\Dom\Node\HtmlNode $v){
    $fields = $v->find('td')->toArray();
    return array_map(function(\PHPHtmlParser\Dom\Node\HtmlNode $v){
        return $v->innerText();
    }, $fields);
}, $rows);

dd($rows);