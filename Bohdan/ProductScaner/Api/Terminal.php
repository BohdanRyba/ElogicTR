<?php

namespace Bohdan\ProductScaner\Api;

interface Terminal
{
    /**
     * @param string $product
     * @param int $count
     * @return $this
     */
    public function scan(string $product, int $count): self;

    /**
     * @return float
     */
    public function getTotal(): float;
}
