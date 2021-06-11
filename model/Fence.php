<?php

namespace model;

/**
 * Class Fence class model for fence item counts and the overall price
 * @package model
 */
class Fence
{
    /**
     * @var int the count of columns in the whole fence
     */
    private int $columnCount;

    /**
     * @var int the count of wire-fences in the whole fence
     */
    private int $wireCount;

    /**
     * @var int the price of the whole fence with all items included
     */
    private int $price;

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    public function getColumnCount(): int
    {
        return $this->columnCount;
    }

    public function setColumnCount(int $columnCount): void
    {
        $this->columnCount = $columnCount;
    }

    public function getWireCount(): int
    {
        return $this->wireCount;
    }

    public function setWireCount(int $wireCount): void
    {
        $this->wireCount = $wireCount;
    }
}
