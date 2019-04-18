@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><i class="fa fa-users"></i> Followers</div>
                <div class="card-body table-repsonsive">
                    <table class="table table-hover">
                        <thead>
                            <th>Target</th>
                            <th>Screen Name</th>
                            <th class="text-center">Status</th>
                            <th>Last Change</th>
                        </thead>
                        <tbody>
                            @foreach($followers as $follower)
                            <tr>
                                <td>
                                    <a href="https://twitter.com/{{ $follower->target }}">{{ $follower->target }}</a>
                                </td>
                                <td>
                                    @if($follower->screen_name)
                                    <a href="https://twitter.com/{{ $follower->screen_name }}">
                                        <img src="{{ $follower->avatar_url }}" alt="" width="30" class="rounded-circle mr-2 shadow-sm" />{{ $follower->screen_name }}
                                    </a>
                                    @else
                                    <div class="text-muted">
                                    To be loaded...<br>
                                    <small>{{ $follower->twitter_id }}</small>
                                    </div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($follower->interested === null)
                                    <div class="badge badge-dark" style="font-size:0.9rem">In Queue</div>
                                    @elseif(!$follower->interested)
                                    <div class="badge badge-light" style="font-size:0.9rem">Not Interested</div>
                                    <small class="d-block text-muted">{{ $follower->not_interested_reason }}</small>
                                    @elseif($follower->interested && !$follower->engaged_at)
                                    <div class="badge badge-info text-white" style="font-size:0.9rem">Interested</div>
                                    @elseif($follower->engaged_at && !$follower->converted_at)
                                    <div class="badge badge-primary text-white" style="font-size:0.9rem">Engaged</div>
                                    @else
                                    <div class="badge badge-success text-white" style="font-size:0.9rem">Converted</div>
                                    @endif
                                </td>
                                <td>{{ $follower->updated_at->diffForHumans() }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {!! $followers->links() !!}
                </div>
            
            </div>
        </div>
    </div>
</div>
@endsection
