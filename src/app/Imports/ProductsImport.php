<?php

namespace App\Imports;

use App\Product;
use App\Category;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductsImport extends GenericImport implements ToCollection
{
    public function collection(Collection $file_data)
    {
        $content = $file_data->skip(1);
        $this->initializeResults($content);
        $records = $this->normalizeRecords($content);
        foreach ($records as $key => $value) {
            $this->processRecord($records, $key);
        }
        $this->removeEmptyResults($content);
    }

    private function normalizeRecords($records)
    {
        return $records->map(function($item, $key) {
            $product = [
                'code' => $item[0],
                'name' => $item[1],
                'price' => $item[2],
                'currency' => $item[3],
                'unit' => $item[4],
                'category_code' => $item[5]
            ];
            
            return $this->validatedInputs($product, $key, [
                'code' => 'required|max:127',
                'name' => 'required',
                'price' => 'required|numeric',
                'currency' => 'required',
                'unit' => 'required',
                'category_code' => 'required',
            ]);
        });
    }

    private function processRecord($records, $key)
    {
        if ($this->validateRecord($records, $key)) {
            $updated_record = $records[$key];
            $record = Product::where('code', $updated_record['code'])->first();
            $is_new = false;
            if (!$record) {
                $is_new = true;
                $record = new Product;
                $record->code = $updated_record['code'];
            }
            $record->name = $updated_record['name'];
            $record->price = $updated_record['price'];
            $record->currency = $updated_record['currency'];
            $record->unit = $updated_record['unit'];
            $record->category_id = Category::where('code', $updated_record['category_code'])->first()->id;
            $record->save();
            $this->addInfoMessageToResults($key, $record->code, $is_new);
        }
    }

    private function validateRecord($records, $key)
    {
        $record = $records[$key];
        if ($record === null) {
            return false;
        }
        if ($records->where('code', '=', $record['code'])->count() > 1) {
            $this->addErrorMessageToResults($key, 'Duplicate code "'.$record['code'].'"');
            return false;
        }
        if (!Category::where('code', '=', $record['category_code'])->exists()) {
            $this->addErrorMessageToResults($key, 'Category "'.$record['category_code'].'" does not exist.');
            return false;
        }
        return true;
    }
}
