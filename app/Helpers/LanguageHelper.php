<?php

if (!function_exists('get_direction')) {
    /**
     * Get current language direction
     *
     * @return string
     */
    function get_direction()
    {
        return in_array(app()->getLocale(), ['fa', 'ar']) ? 'rtl' : 'ltr';
    }
}

if (!function_exists('is_rtl')) {
    /**
     * Check if current language is RTL
     *
     * @return bool
     */
    function is_rtl()
    {
        return in_array(app()->getLocale(), ['fa', 'ar']);
    }
}

if (!function_exists('is_ltr')) {
    /**
     * Check if current language is LTR
     *
     * @return bool
     */
    function is_ltr()
    {
        return !in_array(app()->getLocale(), ['fa', 'ar']);
    }
}

if (!function_exists('translate_or_default')) {
    /**
     * Translate with fallback
     *
     * @param string $key
     * @param string $default
     * @param array $replace
     * @return string
     */
    function translate_or_default($key, $default = '', $replace = [])
    {
        $translation = __($key, $replace);
        return $translation === $key ? $default : $translation;
    }
}

if (!function_exists('get_locale_name')) {
    /**
     * Get full name of current locale
     *
     * @return string
     */
    function get_locale_name()
    {
        $names = [
            'fa' => 'فارسی',
            'en' => 'English',
            'ar' => 'العربية'
        ];
        
        return $names[app()->getLocale()] ?? 'فارسی';
    }
}

if (!function_exists('get_locale_flag')) {
    /**
     * Get flag emoji for current locale
     *
     * @return string
     */
    function get_locale_flag()
    {
        $flags = [
            'fa' => '🇮🇷',
            'en' => '🇬🇧',
            'ar' => '🇸🇦'
        ];
        
        return $flags[app()->getLocale()] ?? '🇮🇷';
    }
}
