<?php

namespace Ofmtools\Visitors;

enum BlockType: int
{
    case OTHER = 0;
    case STAND_OPEN = 1;
    case STAND_ROOF = 2;
    case SIT_OPEN = 3;
    case SIT_ROOF = 4;

    public static function getBlockTypeByString(string $typeString)
    :BlockType
    {
        switch($typeString){
            case "Stehplätze, ohne Dach": return self::STAND_OPEN;
            case "Stehplätze, überdacht": return self::STAND_ROOF;
            case "Sitzplätze, ohne Dach": return self::SIT_OPEN;
            case "Sitzplätze, überdacht": return self::SIT_ROOF;
            default: return self::OTHER;
        }
    }

    public static function getStringByInt(int $typeInt)
    :string
    {
        switch($typeInt){
            case 1: return "Stehplätze, ohne Dach";
            case 2: return "Stehplätze, überdacht";
            case 3: return "Sitzplätze, ohne Dach";
            case 4: return "Sitzplätze, überdacht";
            default: return "Unbekannt";
        }
    }
}