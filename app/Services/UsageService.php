<?php

namespace App\Services;

use App\Models\Usage;
use Illuminate\Http\Request;

class UsageService
{
    public function all()
    {
        return Usage::all();
    }

    public function find($id)
    {
        return Usage::find($id);
    }

    public function store(Request $request)
    {
        $usage = new Usage;
        $usage->name = $request->input('name');
        $usage->save();
        return $usage;
    }

    public function update(Request $request, $id)
    {
        $usage = Usage::find($id);
        if ($usage === null) return null;
        $usage->name = $request->input('name');
        $usage->save();
        return $usage;
    }

    public function destroy($id)
    {
        $usage = Usage::find($id);
        if ($usage === null) return null;
        $usage->safeDelete();
        return $usage;
    }
}
