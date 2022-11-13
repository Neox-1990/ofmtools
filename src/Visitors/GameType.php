<?php

namespace Ofmtools\Visitors;

use MathPHP\NumberTheory\Integer;

enum GameType: int
{
    case OTHER = 0;
    case LEAGUE = 1;
    case FRIENDLY = 2;
    case CUP = 3;
}