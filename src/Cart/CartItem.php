<?php

namespace App\Cart;

use App\Entity\Product;

class CartItem {

    public $product;
    public $qte;

    public function __construct(Product $product, float $qte)
    {
        $this->product =$product;
        $this->qte =$qte;
    }

    public function getTotal() : float
    {
        return $this->product->getPrice() * $this->qte;
    }

}