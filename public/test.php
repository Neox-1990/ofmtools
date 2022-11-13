<?php

require_once "../vendor/autoload.php";

use PHPHtmlParser\Dom;
use Ofmtools\Visitors\OfmXlsParser;

$test = new Ofmtools\Test();

$test->test();

$file = file_get_contents("../debug/Zuschauer_2541457_104_32.xls");
$dom = new Dom;
$dom->loadStr($file);
$parser = new OfmXlsParser();
$rows = $parser->groupEntries($parser->parseDocument($dom));

dd($rows);