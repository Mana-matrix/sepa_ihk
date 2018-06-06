@extends('layouts.app')
<?php use App\Http\Controllers\ApiController;


?>
@section('left')
    @include('layouts.sepa_form')
@endsection
@section('right')
    @if(Auth::check())



        <!--div class="my-3"><?=""// ApiController::guzzle() ?> </div-->

    @endif
@endsection
<!-- end section right-->
@section('content')
    @if(Auth::check())
        <?php

        //$request = Request::create('/clients/new', 'GET');
        $response = Route::dispatch(Request::create('/clients/new', 'GET'));
        $new=$response->getData();
        $response = Route::dispatch(Request::create('/clients/old', 'GET'));
        $old=$response->getData();
        $response = Route::dispatch(Request::create('/clients/disabled', 'GET'));
        $disabled=$response->getData();

        ?>

        <div class="row">
            <div class="col-md-12 ">
                <div class="panel panel-success">
                @include ('layouts.menu')

                        @includeWhen(count(collect($new)['data']), 'layouts/table', ['text'=>'new clients','chars'=>collect($new)['data']])
                        @includeWhen(count(collect($old)['data']), 'layouts/table', ['text'=>'old clients','chars'=>collect($old)['data']])
                        @includeWhen(count(collect($disabled)['data']), 'layouts/table', ['text'=>'disabled clients','chars'=>collect($disabled)['data']])



                {{$response->original->links()}}



                </div>

            </div>
        </div>
    @endif
@endsection

