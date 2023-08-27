<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

<body class="container">
<?php

require_once "../vendor/autoload.php";

use Ofmtools\Visitors\BlockEntry;
use PHPHtmlParser\Dom;
use Ofmtools\Visitors\OfmXlsParser;

$test = new Ofmtools\Test();

$test->test();

$file01 = file_get_contents("../debug/Zuschauer_2541457_103_33.xls");
$file02 = file_get_contents("../debug/Zuschauer_2541457_104_34.xls");
$file03 = file_get_contents("../debug/Zuschauer_2541457_105_34.xls");
$file04 = file_get_contents("../debug/Zuschauer_2541457_106_34.xls");
$file05 = file_get_contents("../debug/Zuschauer_2541457_107_34.xls");
$file06 = file_get_contents("../debug/Zuschauer_2541457_108_34.xls");
$file07 = file_get_contents("../debug/Zuschauer_2541457_109_34.xls");
$file08 = file_get_contents("../debug/Zuschauer_2541457_110_34.xls");
$file09 = file_get_contents("../debug/Zuschauer_2541457_111_34.xls");
$file10 = file_get_contents("../debug/Zuschauer_2541457_112_34.xls");

$dom01 = (new Dom)->loadStr($file01);
$dom02 = (new Dom)->loadStr($file02);
$dom03 = (new Dom)->loadStr($file03);
$dom04 = (new Dom)->loadStr($file04);
$dom05 = (new Dom)->loadStr($file05);
$dom06 = (new Dom)->loadStr($file06);
$dom07 = (new Dom)->loadStr($file07);
$dom08 = (new Dom)->loadStr($file08);
$dom09 = (new Dom)->loadStr($file09);
$dom10 = (new Dom)->loadStr($file10);

$parser = new OfmXlsParser();

$array01 = $parser->parseDocument($dom01);
$array02 = $parser->parseDocument($dom02);
$array03 = $parser->parseDocument($dom03);
$array04 = $parser->parseDocument($dom04);
$array05 = $parser->parseDocument($dom05);
$array06 = $parser->parseDocument($dom06);
$array07 = $parser->parseDocument($dom07);
$array08 = $parser->parseDocument($dom08);
$array09 = $parser->parseDocument($dom09);
$array10 = $parser->parseDocument($dom10);

$megarray = array_merge($array01,$array02,$array03,$array04,$array05,$array06,$array07,$array08,$array09,$array10);

$groups = $parser->groupEntries($megarray);

/*
foreach($groups as $t => $f){
    echo "<h1>".$t."</h1>";
    echo "<table class=\"table\">".
        "<tr>".
            "<th>Preis</th>".
            "<th>Teamprodukt</th>".
            "<th>Kapazität</th>".
            "<th>Effektive Kapazität</th>".
            "<th>Zuschauer</th>".
            "<th>Zustand</th>".
            "<th>Effektiver Zustand</th>".
            "<th>Auslastung</th>".
            "<th>Effektive Auslastung</th>".
        "</tr>";
        foreach($f as $rows){
            foreach($rows as $entry){
                echo "
                <tr>
                    <td>".$entry->entryfee."</td>
                    <td>".($entry->home*$entry->away)."</td>
                    <td>".$entry->capacity."</td>
                    <td>".$entry->effective_capacity."</td>
                    <td>".$entry->visitors."</td>
                    <td>".$entry->condition."</td>
                    <td>".$entry->effective_condition."</td>
                    <td>".$entry->utilization."</td>
                    <td>".$entry->effective_utilization."</td>
                </tr>";
            }
        }
    echo "</table>";
}
*/
//dd($groups);
$factors = $parser->calculateFactors($groups);
//dd($factors);

foreach($factors as $type => $entries){
    echo "<h3>".BlockEntry::decipherFingerprint($type)."</h3>";
    echo "
<table class=\"table\">
    <tr>
        <th>Preis</th>
        <th>M Faktor</th>
        <th>Standardabweichung</th>
    </tr>";
    //ksort($entries, SORT_NATURAL);
    foreach($entries as $price => $data){
        $fee = explode('_',$price);
        $fee = intval($fee[1]);
        echo "
    <tr>
        <td>".$fee." €</td>
        <td>".number_format($data['average m'],32)."</td>
        <td>".number_format($data['derivation m'],32)."</td>
    </tr>
        ";
    }
    echo "</table><br><br><br>";
}

echo "<h3>Dataset</h3>";
echo "<textarea>".json_encode($factors)."</textarea>";

?>

</body>