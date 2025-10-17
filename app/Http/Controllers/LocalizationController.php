<?php

namespace App\Http\Controllers;

class LocalizationController extends Controller
{
    public function switch($locale)
    {
        if (! in_array($locale, ['en', 'es'])) {
            abort(400, 'Invalid locale');
        }

        session()->put('locale', $locale);

        return redirect()->back();
    }
}
