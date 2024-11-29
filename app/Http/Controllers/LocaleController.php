<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    public function changeLocale($locale)
    {
        $availableLocales = ['en', 'fr'];

        if (in_array($locale, $availableLocales)) {
            Session::put('locale', $locale);
        }

        return redirect()->back();
    }
}
