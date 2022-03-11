<?php

namespace App\Http\Controllers;

class LocaleController extends Controller
{
    public function changeLocale($locale)
    {
        if ($locale === 'en' || $locale === 'ru'){
            session(['locale' => $locale]);
            return redirect()->back();
        }
        else abort(404);
    }
}
