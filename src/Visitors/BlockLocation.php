<?php

namespace Ofmtools\Visitors;

enum BlockLocation: int
{
    case OTHER = 0;
    case NORTH = 1;
    case EAST = 2;
    case SOUTH = 3;
    case WEST = 4;

    public static function getBlockLocationByString(string $locationString)
    :BlockLocation
    {
        switch($locationString){
            case "Nord": return self::NORTH;
            case "Ost": return self::EAST;
            case "Süd": return self::SOUTH;
            case "West": return self::WEST;
            default: return self::OTHER;
        }
    }

    public static function getStringByInt(int $locationInt)
    :string
    {
        switch($locationInt){
            case 1: return "Nord";
            case 2: return "Ost";
            case 3: return "Süd";
            case 4: return "West";
            default: return "Unbekannt";
        }
    }
}