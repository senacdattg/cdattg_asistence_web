<?php

if (!function_exists('routes_path')) {
    function routes_path($path = '') {
        return base_path('routes' . ($path ? DIRECTORY_SEPARATOR . $path : $path));
    }
}
