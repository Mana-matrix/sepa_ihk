@extends('layouts.app')
@section('left')
@include('layouts.sepa_form')
@endsection

@section('right')
@endsection
<!-- end section right-->
@section('content')
    @if(Auth::check())
        <?php
        $response = Route::dispatch(Request::create('/clients/disabled', 'GET'));
        $data=$response->getData();
        ?>
        <div class="row">
            <div class="col-md-12 ">
                <div class="panel panel-success">
                    @include ('layouts.menu')
                    @includeWhen(count(collect($data)['data']), 'layouts/table', ['text'=>'disabled clients','chars'=>collect($data)['data']])
                    {{$response->original->links()}}
                </div>
            </div>
        </div>
    @endif
@endsection

