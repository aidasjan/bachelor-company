<?php

namespace App\Imports;

use App\Category;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CategoriesImport extends GenericImport implements ToCollection
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
            $category = [
                'code' => $item[0],
                'name' => $item[1],
                'name_ru' => $item[2],
            ];
            
            return $this->validatedInputs($category, $key, [
                'code' => 'required|max:127',
                'name' => 'required',
                'name_ru' => 'required',
            ]);
        });
    }

    private function processRecord($records, $key)
    {
        if ($this->validateRecord($records, $key)) {
            $updated_record = $records[$key];
            $record = Category::where('code', $updated_record['code'])->first();
            $is_new = false;
            if (!$record) {
                $is_new = true;
                $record = new Category;
                $record->code = $updated_record['code'];
            }
            $record->name = $updated_record['name'];
            $record->name_ru = $updated_record['name_ru'];
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
        return true;
    }
}
