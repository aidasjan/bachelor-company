<?php

namespace App\Imports;

use App\Product;
use App\Subcategory;
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
                'subcategory_code' => $item[5]
            ];
            
            return $this->validatedInputs($product, $key, [
                'code' => 'required|max:127',
                'name' => 'required',
                'price' => 'required|numeric',
                'currency' => 'required',
                'unit' => 'required',
                'subcategory_code' => 'required',
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
            $record->subcategory_id = Subcategory::where('code', $updated_record['subcategory_code'])->first()->id;
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
        if (!Subcategory::where('code', '=', $record['subcategory_code'])->exists()) {
            $this->addErrorMessageToResults($key, 'Subcategory "'.$record['subcategory_code'].'" does not exist.');
            return false;
        }
        return true;
    }
}
