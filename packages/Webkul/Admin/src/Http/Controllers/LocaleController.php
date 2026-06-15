<?php

namespace Webkul\Admin\Http\Controllers;

class LocaleController extends Controller
{
    /**
     * Change application locale.
     *
     * @param  string  $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch($locale)
    {
        if (array_key_exists($locale, config('app.available_locales'))) {
            session()->put('locale', $locale);
        }

        return redirect()->back();
    }
}
