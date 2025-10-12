@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">CV Verification</div>

                <div class="card-body">
                    <h1 class="text-2xl font-bold text-green-600">Verification Successful</h1>
                    <p>This CV is authentic.</p>
                    <ul>
                        <li>User: {{ $cvVersion->user->name }}</li>
                        <li>Template: {{ $cvVersion->template }}</li>
                        <li>Generated on: {{ $cvVersion->created_at->format('Y-m-d H:i') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
