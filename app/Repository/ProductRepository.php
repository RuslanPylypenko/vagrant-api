<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductLine;
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

        if ($orderBy = $filters['orderBy'] ?? null) {
            $query->orderBy($orderBy, $filters['orderDirection'] ?? 'asc');
        }


        return $query->paginate(self::ITEMS_PER_PAGE);
    }

    public function findLines(?string $search = null): array
    {
        $query = ProductLine::query();

        if ($search) {
            $query->where('productLine', 'LIKE', "%{$search}%");
        }

        return array_map(
            static fn(ProductLine $productLine) => $productLine->productLine,
            $query->get()->all()
        );
    }
}
