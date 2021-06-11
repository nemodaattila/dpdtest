<?php

namespace controller;

use JetBrains\PhpStorm\Pure;

/**
 * Class Fence controller for Fence - counting item numbers and prices
 * @package controller
 */
class Fence
{
    /**
     * @var \model\Fence model for the Fence controller
     */
    private \model\Fence $model;

    public function __construct()
    {
        $this->model = new \model\Fence();
    }

    /**
     * calculates the required count of columns and wire-fences
     * @param \model\RectangleArea $area contains the data of the area to be calculated
     */
    public function calculateFenceItems(\model\RectangleArea $area)
    {
        /**
         * minimum szélességeket megvizsgálni
         * minimum 6 méter
         */
        $width = $area->getWidth();
        $height = $area->getHeight();
        $width = $this->subtractGateAndCornerFromWidth($width);
        $height = $this->subtractGateAndCornerFromWidth($height);
        [$widthColumns, $widthWire, $widthSurplus] = $this->calcColumnAndWireCount($width);
        [$heightColumns, $heightWire, $heightSurplus] = $this->calcColumnAndWireCount($height);
        $allColumnCount = ($widthColumns + $heightColumns) * 2;
        $allWireCount = ($widthWire + $heightWire) * 2;
        $allSurplusWire = ($heightSurplus + $widthSurplus) * 2;
        $allWireCount -= floor($allSurplusWire / 2);
        $this->model->setColumnCount($allColumnCount);
        $this->model->setWireCount($allWireCount);
    }

    /**
     * subtract a corner (1 M (0,5 + 0,5)) a gate(5 M) from the side width
     * @param float $width the width of a side
     * @return float the reduced width
     */
    private function subtractGateAndCornerFromWidth(float $width): float
    {
        return $width - (CORNER_WIDTH_IN_METER + GATE_WIDTH_IN_METER);
    }

    /**
     * calculates the required count of columns, wire-fences and the width of the surplus wire-fence for ONE side
     * @param float $width the width of the size
     * @return array [int <columnCount>, int <wireFenceCount>, float <surplusWireFenceWidth>]
     */
    private function calcColumnAndWireCount(float $width): array
    {
        if ($width < 2) {
            return [0, 2, 2 - $width];
        }
        $columnNum = (int)abs(ceil(($width - 4) / 2.2));
        $wireNum = $columnNum + 2;
        $surplusWire = ($columnNum * COLUMN_WIDTH_IN_METER + $wireNum * WIRE_WIDTH_IN_METER) - $width;
        return [$columnNum, $wireNum, $surplusWire];
    }

    /**
     * calculates the price of the full fence based on the count and price of items
     */
    public function calculateFencePrice(): void
    {
        $this->model->setPrice(CORNER_COUNT * CORNER_PRICE + COLUMN_PRICE * $this->model->getColumnCount() +
            WIRE_PRICE * $this->model->getWireCount() + GATE_COUNT * GATE_PRICE);
    }

    /**
     * returns the price of the full fence
     * @return int the price
     */
    public function getPrice(): int
    {
        return $this->model->getPrice();
    }

}
