<?php

namespace Ofmtools\Visitors;

enum BlockType: int
{
    CASE OTHER = 0;
    case STAND_OPEN = 1;
    case STAND_ROOF = 2;
    case SIT_OPEN = 3;
    case SIT_ROOF = 4;
}