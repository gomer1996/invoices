@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <Invoices-Component :user="{{ Auth::user() }}"></Invoices-Component>
        </div>
    </div>
</div>
@endsection
