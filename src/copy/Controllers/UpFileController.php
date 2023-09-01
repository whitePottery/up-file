<?php

    namespace App\Http\Controllers\UpFile;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;

    class UpFileController extends Controller
    {


        public function index() {


            return view('up-file.example');

        }
    }
