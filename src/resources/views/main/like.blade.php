@extends('../../layouts/my')
@section('title', 'Like')
@section('content')
<div class="container">
    <div>
    <h2>Post</h2>
    </div>
    <div class="card mb-3" style="max-width: 1080px;">
        <div class="row no-gutters">
            <div class="col-md-4">
                <img src="data:image/png;base64,{{ $image->image }}" class="card-img-top" alt="...">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h3 class="card-title">{{ $image->caption }}</h3>
                    <p class="card-text text-muted">
                    Posted by <a href="/user?uid={{ $image->user_id }}">{{ App\User::where('id', $image->user_id)->first()->github_id }} </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <hr>

    <h2>Liked users</h2>
    <!-- View uploaded image -->
    @isset ($users)
        @foreach ($users as $d)
            <a href="/user?uid={{ $d->id }}">
            <div class="card mb-3" style="max-width: 270px;">
                <div class="row no-gutters">
                    <div class="col-md-4">
                        <img src="https://github.com/{{ $d->github_id }}.png" class="card-img" alt="..." height="100">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">{{ $d->github_id }}</h5>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        @endforeach
    @endisset
</div>
@endsection