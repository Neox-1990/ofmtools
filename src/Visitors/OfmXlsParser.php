<?php

namespace Ofmtools\Visitors;

use PHPHtmlParser\Dom\Node\HtmlNode;
use PHPHtmlParser\Dom;

class OfmXlsParser
{
    public function parseRow(HtmlNode $row)
    :BlockEntry|null
    {
        $fields = $row->find('td')->toArray();
        if(count($fields) == 16 
            && $fields[5]->innerText() != 'Gesamt' 
            && $fields[5]->innerText() != '' 
            && $fields[5]->innerText() != 'Block' 
            && $fields[6]->innerText() != ''){
            return new BlockEntry(
                //gametype: GameType::getGameTypeByString($fields[0]->innerText()),
                //season: intval($fields[1]->innerText()),
                //matchday: intval($fields[2]->innerText()),
                home: intval($fields[3]->innerText()),
                away: intval($fields[4]->innerText()),
                //blocklocation: BlockLocation::getBlockLocationByString($fields[5]->innerText()),
                blocktype: BlockType::getBlockTypeByString($fields[6]->innerText()),
                condition: floatval(str_replace(',', '.', $fields[7]->innerText()))/100,
                capacity: intval(str_replace('.', '', $fields[8]->innerText())),
                visitors: intval(str_replace('.', '', $fields[9]->innerText())),
                entryfee: intval(str_replace(' €', '', $fields[10]->innerText())),
                income: intval(str_replace(['.', ' €'], ['', ''], $fields[11]->innerText())),
                floodlights: intval($fields[12]->innerText()),
                display: intval($fields[13]->innerText()),
                security: intval($fields[14]->innerText()),
                parking: intval($fields[15]->innerText()),
            );
        }else{
            return null;
        }
    }

    public function parseDocument(Dom $document)
    :array
    {
        $rows = $document->getElementsByTag('tr')->toArray();
        $rows = array_map(function(HtmlNode $node){
            return $this->parseRow($node);
        }, $rows);
        $rows = array_filter($rows, function($entry){
            return !is_null($entry);
        });

        return $rows;
    }

    public function groupEntries(array $entries, bool $filter = true)
    :array
    {
        $group = [];
        foreach($entries as $row){
            if($row->effective_utilization < 1 || !$filter){
                $fingerprint = $row->getFingerprint();
                //$gametype = GameType::getStringByInt($row->gametype->value);
                $entryfee = 'entryfee_'.$row->entryfee;
    
                $group[$fingerprint]/*[$gametype]*/[$entryfee][] = $row;
            }
        }
        foreach($group as $t => $f){
            ksort($group[$t], SORT_NATURAL);
        }

        return $group;
    }

    public function calculateFactors(array $groups)
    :array
    {
        $factors = array_map(function(array $type){
            return array_map(function(array $entryfee){
                $num_entries = sizeof($entryfee);
                $min_m = 1.0;
                $max_m = 0.0;
                $sum_m = array_reduce($entryfee, function($carry, BlockEntry $entry)use(&$min_m,&$max_m){
                    $m = $entry->effective_utilization / ($entry->home * $entry->away);
                    if($m < $min_m) $min_m = $m;
                    if($m > $max_m) $max_m = $m;
                    return $carry + $m;
                },0);
                $average_m = $sum_m/$num_entries;
                $quadsum_m = array_reduce($entryfee, function($carry, BlockEntry $entry)use($average_m){
                    $m = $entry->effective_utilization / ($entry->home * $entry->away);
                    return $carry+(pow($m - $average_m,2));
                },0);
                $variance_m = $quadsum_m / $num_entries;
                $derivation_m = sqrt($variance_m);
                return [
                    'numentries' => $num_entries,
                    'min m' => $min_m,
                    'max m' => $max_m,
                    'sum m' => $sum_m,
                    'span m' => $max_m - $min_m,
                    'average m' => $average_m,
                    'quadsum m' => $quadsum_m,
                    'variance m' => $variance_m,
                    'derivation m' => $derivation_m
                ];
            }, $type);
        }, $groups);

        return $factors;
    }
}