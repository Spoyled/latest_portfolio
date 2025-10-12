@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">CV Verification</div>

                <div class="card-body">
                    <h1 class="text-2xl font-bold text-red-600">Verification Failed</h1>
                    <p>The CV could not be authenticated. The hash is invalid or the record does not exist.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
