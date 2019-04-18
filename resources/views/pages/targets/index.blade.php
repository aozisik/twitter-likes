@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><i class="fa fa-bullseye"></i> Target Accounts</div>
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

                    <div class="list-group">
                    @forelse($targets as $target)
                        <div class="list-group-item">
                            <div class="float-right pt-1">
                            <form action="{{ route('targets.destroy', $target->id) }}" method="POST" class="d-inline">
                                @method('DELETE')
                                @csrf
                                <button class="btn btn-outline-danger btn-sm">
                                <i class="fa fa-trash"></i>
                                </button>
                            </form>
                            </div>
                            <img src="{{ $target->avatar_url }}" alt="" class="d-inline-block rounded-circle float-left  mr-3" width="35" />
                            <div class="d-inline-block" style="line-height:1.2rem">
                                {{ $target->screen_name }} <br>
                                <small class="text-muted">Followers: {{ $target->followers_count }}</small>
                            </div>
                        </div>
                    @empty
                    <div class="text-center my-3 text-muted">No targets added.</div>
                    @endforelse
                    </div>

                    <form method="post" action="{{ url('targets') }}" autocomplete="off"> 
                    @csrf
                        <div class="input-group mt-4">
                            <input type="text" name="screen_name" class="form-control" placeholder="Twitter handle (without @)">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-outline-primary">Add Target</button>
                            </div>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>
@endsection
