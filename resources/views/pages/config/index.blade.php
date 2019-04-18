@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Configuration</div>

                <div class="card-body">
                    
                    <form method="post" action="{{ url('config') }}">
                        @csrf
                        <div class="mb-2">
                            <p class="text-muted"><i class="fa fa-key"></i> Twitter API Keys</p>
                            <div class="px-3">
                                <div class="form-group">
                                    <label>Consumer API Key</label>
                                    <input type="text" name="twitter_consumer_key" class="form-control" value="{{ $configs->get('twitter_consumer_key')->value ?? '' }}" />
                                </div>
                                <div class="form-group">
                                    <label>Consumer API Secret</label>
                                    <input type="password" name="twitter_consumer_secret" class="form-control" value="{{ $configs->get('twitter_consumer_secret')->value ?? '' }}" />
                                </div>
                                <div class="form-group">
                                    <label>Access Token</label>
                                    <input type="text" name="twitter_access_token" class="form-control" value="{{ $configs->get('twitter_access_token')->value ?? '' }}" />
                                </div>
                                <div class="form-group">
                                    <label>Access Token Secret</label>
                                    <input type="password" name="twitter_access_token_secret" class="form-control" value="{{ $configs->get('twitter_access_token_secret')->value ?? '' }}" />
                                </div>
                            </div>
                        </div>

                        <hr>
                        <button class="btn btn-primary">Save</button>
                    </form>
                    
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
