<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExportTranslations extends Command
{
    protected $signature = 'export:translations';
    protected $description = 'Export Laravel translations to a JavaScript file';

    public function handle()
    {
        $languages = ['en', 'jp', 'vi'];
        $translations = [];

        foreach ($languages as $lang) {
            app()->setLocale($lang);
            $translations[$lang] = [
                'messages' => trans('messages'),
                'validation' => trans('validation'),
                'labels' => trans('labels'),
                'auth' => trans('auth'),
                'units' => trans('units'),
            ];
        }

        $path = public_path('translations.js');
        File::put($path, 'window.translations = ' . json_encode($translations, JSON_PRETTY_PRINT) . ';');
        $this->info('Translations exported to translations.js');
    }
}
