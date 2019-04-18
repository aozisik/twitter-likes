@extends('layouts.app')

@section('content')
<div class="container">
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
    <div class="row justify-content-center">
        <div class="col-md-6"> 
            <form method="post" action="{{ url('config') }}" autocomplete="off"> 
                @csrf
                <div class="card">
                    <div class="card-header"><i class="fa fa-key"></i> Twitter API Keys</div>
                    <div class="card-body">                                      
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
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <form method="post" action="{{ url('config') }}" autocomplete="off"> 
                @csrf
                <input type="hidden" name="tuning" value="1" />
                <div class="card">
                    <div class="card-header"><i class="fa fa-wrench"></i> Fine Tuning</div>
                    <div class="card-body">    
                    
                        <div class="form-group">
                            <label>max_likes_per_day</label>
                            <input type="number" name="max_likes_per_day" class="form-control" value="{{ $configs->get('max_likes_per_day') ?? 1000 }}" />
                        </div>
                        <div class="form-group">
                            <label>last_tweet_max_days_ago</label>
                            <input type="number" name="last_tweet_max_days_ago" class="form-control" value="{{ $configs->get('last_tweet_max_days_ago') ?? 3 }}" />
                            <div class="help-block mt-1 text-muted">Back off and try later if user's last tweet is older than this many days.</div>
                        </div>
                        <div class="form-group">
                            <label>notweet_days_to_lose_interest</label>
                            <input type="number" name="notweet_days_to_lose_interest" class="form-control" value="{{ $configs->get('notweet_days_to_lose_interest') ?? 30 }}" />
                            <div class="help-block mt-1 text-muted">Stop tracking the user if their last tweet is older than this many days.</div>
                        </div>
                        <div class="form-group">
                            <label>recheck_tweets_days</label>
                            <input type="number" name="recheck_tweets_days" class="form-control" value="{{ $configs->get('recheck_tweets_days') ?? 1 }}" />
                            <div class="help-block mt-1 text-muted">If the last tweet is too old, come back in this many days to re-check.</div>
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
