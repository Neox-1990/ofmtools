<?php

namespace Ofmtools\Visitors;

use MathPHP\NumberTheory\Integer;

enum GameType: int
{
    case OTHER = 0;
    case LEAGUE = 1;
    case FRIENDLY = 2;
    case CUP = 3;

    public static function getGameTypeByString(string $TypeString)
    :GameType
    {
        switch($TypeString){
            case "Liga": return self::LEAGUE;
            case "Friendly": return self::FRIENDLY;
            case "OFM-Pokal": return self::CUP;
            default: return self::OTHER;
        }
    }

    public static function getStringByInt(int $TypeInt)
    :string
    {
        switch($TypeInt){
            case 1: return "Liga";
            case 2: return "Friendly";
            case 3: return "OFM-Pokal";
            default: return "Unbekannt";
        }
    }
}