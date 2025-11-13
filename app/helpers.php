<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('isRole')) {
    function isRole(...$roles)
    {
        return Auth::check() && in_array(Auth::user()->role, $roles);
    }
}
