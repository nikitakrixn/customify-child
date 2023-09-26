<?php

class WC_Product_TechnicalTask extends WC_Product
{

    protected string $slug = 'technical_task';

    /**
     * @return string
     */
    public function get_type(): string
    {
        return $this->slug; // ярлык типа товара
    }
}