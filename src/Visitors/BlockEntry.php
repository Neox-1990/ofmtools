<?php

namespace Ofmtools\Visitors;

class BlockEntry
{
    public readonly int $effective_capacity;
    public readonly float $effective_condition;
    public readonly float $effective_utilization;

    public function __construct(
        public readonly GameType $gametype,
        public readonly int $season,
        public readonly int $matchday,
        public readonly int $home,
        public readonly int $away,
        public readonly BlockLocation $blocklocation,
        public readonly BlockType $blocktype,
        public readonly float $condition,
        public readonly int $capacity,
        public readonly int $visitors,
        public readonly int $entryfee,
        public readonly int $income,
        public readonly int $floodlights,
        public readonly int $display,
        public readonly int $security,
        public readonly int $parking
    )
    {
        $this->effective_condition = (300.0 + $condition)/4;
        $this->effective_capacity = $capacity * $this->effective_condition;
        $this->effective_utilization = $this->effective_capacity == 0 ? 0.0 : $visitors / $this->effective_capacity;
    }

    /**
     * Gets unique string to group identical grandstands together, independent of BlockLocation
     */
    public function getFingerprint()
    :string
    {
        return $this->blocktype->value.$this->capacity.$this->floodlights.$this->display.$this->security.$this->parking;
    }

    public function getConditionNormalized()
    :float
    {
        return $this->condition/100.0;
    }

    public function getEffectiveConditionNormalized()
    :float
    {
        return $this->effective_condition/100.0;
    }
}