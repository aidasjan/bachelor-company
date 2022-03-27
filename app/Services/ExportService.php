<?php

namespace App\Services;

use App\Exports\CategoriesExport;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportService
{
    public function export($type)
    {
        if ($this->isExportTypeSupported($type)) {
            return Excel::download($this->getExportByType($type), $type . '.xlsx');
        } else return null;
    }

    public function isExportTypeSupported($type)
    {
        return in_array($type, ['products', 'categories']);
    }

    private function getExportByType($type)
    {
        switch ($type) {
            case 'categories':
                return new CategoriesExport;
            case 'products':
                return new ProductsExport;
            default:
                return null;
        }
    }
}
