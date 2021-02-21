@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="text-center">
                        <a href="{{ url('targets') }}" class="d-inline-block text-center border rounded bg-secondary text-white p-4 mr-4 mb-4" style="width:12rem;">    
                            <h1>{{ $targets }}</h1>
                            Target {{ str_plural('Account', $targets) }}
                        </a>

                        <a href="{{ url('followers') }}" class="d-inline-block text-center border rounded bg-info text-white p-4 mr-4 mb-4" style="width:12rem;">    
                            <h1>{{ $followers }}</h1>
                            Potential {{ str_plural('Follower', $followers) }}
                        </a>
                        <br>
                        <a href="{{ url('followers?filter=engaged') }}" class="d-inline-block text-center border rounded bg-primary text-white p-4 mr-4 mb-4" style="width:12rem;">    
                            <h1>{{ $engaged }}</h1>
                            Engaged
                        </a>
                        
                        <a href="{{ url('followers?filter=converted') }}" class="d-inline-block text-center border rounded bg-success text-white p-4 mr-4 mb-4" style="width:12rem;">    
                            <h1>{{ $conversions }}</h1>
                            Conversions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
