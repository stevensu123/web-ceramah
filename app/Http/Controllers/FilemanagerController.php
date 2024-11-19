<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use UniSharp\LaravelFilemanager\Controllers\LfmController;

class FilemanagerController extends controller
{

    public function index(Request $request)

    {   

        return view('filemanager.file-manager');

    }
}
