<?php

namespace App\Helpers;

class UrlHelper
{
    public static function convertUrlsToLinks($text)
    {
        $text = e($text);

        $pattern = '/\bhttps:\/\/(?:www\.)?([a-zA-Z0-9-]+\.[a-zA-Z]{2,6})(?:[\/\w\.-]*)\b/i';

        $text = preg_replace_callback($pattern, function ($matches) {
            $url = $matches[0];

            return '<a href="' . $url . '" target="_blank" class="erah-link">' . $url . '</a>';
        }, $text);

        return $text;
    }
}
