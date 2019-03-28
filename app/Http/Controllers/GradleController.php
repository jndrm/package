<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GradleController extends Controller
{
    public function index()
    {
    }

    public function show($package)
    {
        return $package;
    }

    public function metadata($package)
    {
        return "{$package}-metadata.xml";
    }
}
