<?php

namespace App\Imports;

use Validator;

abstract class GenericImport
{
    protected $importResults = [];

    public function getImportResults()
    {
        return $this->importResults;
    }

    protected function initializeResults($records)
    {
        $this->importResults = [
            'errors' => collect([]),
            'info' => collect([]),
        ];

        foreach ($records as $key=>$value) {
            $this->importResults['errors']->put($key+1, collect([]));
            $this->importResults['info']->put($key+1, collect([]));
        }
    }

    protected function removeEmptyResults()
    {
        $this->importResults['errors'] = $this->importResults['errors']->filter(function($item) { return $item->count() > 0; });
        $this->importResults['info'] = $this->importResults['info']->filter(function($item) { return $item->count() > 0; });
    }

    protected function addInfoMessageToResults($key, $code, $is_new)
    {
        $this->importResults['info'][$key+1]->push("\"$code\" has been successfully ".($is_new ? "added" : "updated"));
    }

    protected function addErrorMessageToResults($key, $message)
    {
        $this->importResults['errors'][$key+1]->push($message);
    }

    protected function addErrorMessagesToResults($key, $messages)
    {
        $this->importResults['errors'][$key+1] = $this->importResults['errors'][$key+1]->concat($messages);
    }

    protected function validatedInputs($record, $key, $rules) {
        $validator = Validator::make($record, $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $this->addErrorMessagesToResults($key, collect($messages), 'errors');
            return null;
        } else {
            return $record;
        }
    }
}
