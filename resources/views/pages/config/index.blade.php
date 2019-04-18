@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form method="post" action="{{ url('config') }}" autocomplete="off"> 
                @csrf
                <div class="card">
                    <div class="card-header"><i class="fa fa-key"></i> Twitter API Keys</div>
                    <div class="card-body">    
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif                                            
                        <div class="form-group">
                            <label>Consumer API Key</label>
                            <input type="text" name="twitter_consumer_key" class="form-control" value="{{ $configs->get('twitter_consumer_key') ?? '' }}" />
                        </div>
                        <div class="form-group">
                            <label>Consumer API Secret</label>
                            <input type="password" name="twitter_consumer_secret" class="form-control" value="{{ $configs->get('twitter_consumer_secret') ?? '' }}" />
                        </div>
                        <div class="form-group">
                            <label>Access Token</label>
                            <input type="text" name="twitter_access_token" class="form-control" value="{{ $configs->get('twitter_access_token') ?? '' }}" />
                        </div>
                        <div class="form-group">
                            <label>Access Token Secret</label>
                            <input type="password" name="twitter_access_token_secret" class="form-control" value="{{ $configs->get('twitter_access_token_secret') ?? '' }}" />
                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <button class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
