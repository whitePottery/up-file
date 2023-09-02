<?php

namespace UpFile\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UpFileMainController extends Controller
{


    public function index()
    {
        return view('up-file::main');
    }
}
