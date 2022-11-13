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
                gametype: GameType::getGameTypeByString($fields[0]->innerText()),
                season: intval($fields[1]->innerText()),
                matchday: intval($fields[2]->innerText()),
                home: intval($fields[3]->innerText()),
                away: intval($fields[4]->innerText()),
                blocklocation: BlockLocation::getBlockLocationByString($fields[5]->innerText()),
                blocktype: BlockType::getBlockTypeByString($fields[6]->innerText()),
                condition: floatval(str_replace(',', '.', $fields[7]->innerText())),
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

    public function groupEntries(array $entries)
    :array
    {
        $group = [];
        foreach($entries as $row){
            $fingerprint = $row->getFingerprint();
            $gametype = GameType::getStringByInt($row->gametype->value);
            $entryfee = 'entryfee_'.$row->entryfee;

            $group[$fingerprint][$gametype][$entryfee][] = $row;
        }

        return $group;
    }
}