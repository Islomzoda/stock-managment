<?php

namespace App\Services;

class Calc{
    static public function sell($data)
    {
        $quantity = $data['quantity'];
        $unit = $data['unit'];
        $price = $data['price'];
        switch ($unit) {
            case '1/шт':
                $result = $quantity * $price;
                break;
            
            case '1/кг':
                $result = ($quantity / 1000) * $price;
                break;
            
            case '100/гр':
                $result = ($quantity / 100) * $price;
                break;
            
            default:
                $result = 0;
                break;
        }
      
        return $result;
    }
}