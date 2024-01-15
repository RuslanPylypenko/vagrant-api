<?php

namespace App\Repository;

use App\Entity\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository
{
    private const ITEMS_PER_PAGE = 15;

    public function find(array $filters): LengthAwarePaginator
    {
        $query = Product::query();

        if ($searchTerm = $filters['search'] ?? null) {
            $query
                ->where(function ($q) use ($searchTerm) {
                    $q->where('productCode', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('productName', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('productVendor', 'LIKE', "%{$searchTerm}%");
                });

        }

        if ($productLine = $filters['filters']['productLine'] ?? null) {
            $query->where('productLine', '=', $productLine);
        }

        if ($qtyMin = $filters['filters']['qty']['min'] ?? null) {
            $query->where('quantityInStock', '>=', $qtyMin);
        }

        if ($qtyMax = $filters['filters']['qty']['max'] ?? null) {
            $query->where('quantityInStock', '<=', $qtyMax);
        }

        if ($qtyMin = $filters['filters']['price']['min'] ?? null) {
            $query->where('buyPrice', '>=', $qtyMin);
        }

        if ($qtyMax = $filters['filters']['price']['max'] ?? null) {
            $query->where('buyPrice', '<=', $qtyMax);
        }


        return $query->paginate(self::ITEMS_PER_PAGE);
    }
}
