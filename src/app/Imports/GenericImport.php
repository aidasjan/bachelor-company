<?php

namespace App\Imports;

use Validator;

abstract class GenericImport
{
    protected $import_results = [];

    public function getImportResults()
    {
        return $this->import_results;
    }

    protected function initializeResults($records)
    {
        $this->import_results = [
            'errors' => collect([]),
            'info' => collect([]),
        ];

        foreach ($records as $key=>$value) {
            $this->import_results['errors']->put($key+1, collect([]));
            $this->import_results['info']->put($key+1, collect([]));
        }
    }

    protected function removeEmptyResults()
    {
        $this->import_results['errors'] = $this->import_results['errors']->filter(function($item) { return $item->count() > 0; });
        $this->import_results['info'] = $this->import_results['info']->filter(function($item) { return $item->count() > 0; });
    }

    protected function addInfoMessageToResults($key, $code, $is_new)
    {
        $this->import_results['info'][$key+1]->push("\"$code\" has been successfully ".($is_new ? "added" : "updated"));
    }

    protected function addErrorMessageToResults($key, $message)
    {
        $this->import_results['errors'][$key+1]->push($message);
    }

    protected function addErrorMessagesToResults($key, $messages)
    {
        $this->import_results['errors'][$key+1] = $this->import_results['errors'][$key+1]->concat($messages);
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
