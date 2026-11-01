<?php

namespace App\Exceptions;

class InsufficientStockException extends \RuntimeException
{
    public function __construct(public array $items)
    {
        parent::__construct('Stok tidak mencukupi untuk: ' . implode(', ', $items));
    }
}