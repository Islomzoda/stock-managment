<?php

namespace App\Services;

class Calc{
    static public function sell($data)
    {
        $quantity = $data['quantity'];
        $unit = $data['unit'];
        $price = $data['price'];

        if ($unit === 'шт') {
            $result = $quantity * $price;
        } elseif ($unit === 'гр') {
            $result = ($quantity / 100) * $price;
        } else {
            // Обработка некорректного значения unit
            $result = 0;
        }
        return $result;
    }
}