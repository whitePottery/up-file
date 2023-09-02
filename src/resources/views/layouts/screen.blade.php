@extends('up-file::layouts.app')

@section("sidebar")

@endsection

@section('app-col')

    <div class="container-fluid px-3">


        <div class="row justify-content-left  ">


            @include('up-file::layouts.sidebar')

            <div class="col-md-8 mt-4 mainContent">
{{-- @if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>
            {{ $error }}
        </li>
        @endforeach
    </ul>
</div>
@endif --}}
                @yield('content')
            </div>

        </div>
    </div>

@endsection
