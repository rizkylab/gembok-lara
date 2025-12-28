<?php

use App\Models\AppSetting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('appSetting')) {
    /**
     * Get app setting value with caching
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function appSetting($key, $default = null)
    {
        return Cache::remember("app_setting_{$key}", 3600, function () use ($key, $default) {
            return AppSetting::getValue($key, $default);
        });
    }
}

if (!function_exists('companyName')) {
    /**
     * Get company name from settings
     *
     * @return string
     */
    function companyName()
    {
        return appSetting('company_name', 'GEMBOK LARA');
    }
}

if (!function_exists('companyPhone')) {
    /**
     * Get company phone from settings
     *
     * @return string
     */
    function companyPhone()
    {
        return appSetting('company_phone', '');
    }
}

if (!function_exists('companyEmail')) {
    /**
     * Get company email from settings
     *
     * @return string
     */
    function companyEmail()
    {
        return appSetting('company_email', '');
    }
}

if (!function_exists('companyAddress')) {
    /**
     * Get company address from settings
     *
     * @return string
     */
    function companyAddress()
    {
        return appSetting('company_address', '');
    }
}

if (!function_exists('clearAppSettingsCache')) {
    /**
     * Clear all app settings cache
     *
     * @return void
     */
    function clearAppSettingsCache()
    {
        $keys = ['company_name', 'company_phone', 'company_email', 'company_address', 
                 'default_commission_rate', 'tax_rate', 'currency', 'timezone'];
        
        foreach ($keys as $key) {
            Cache::forget("app_setting_{$key}");
        }
    }
}
