@extends('../../layouts/my')
@section('title', 'User')
@section('content')
<div class="container">
    <div>
    <h2>Profile</h2>
    </div>
    <div class="card mb-3" style="max-width: 540px;">
        <div class="row no-gutters">
            <div class="col-md-4">
                <img src="https://github.com/{{ $user->github_id }}.png" class="card-img" alt="...">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">{{ $user->github_id }}</h5>
                    <p class="card-text">
                        <ul>
                            <li>Liked :: {{ $likes }}</li>
                            <li>Posting :: {{ $posts }}</li>
                        </ul>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <hr>

    <!-- View uploaded image -->
    <h2>Posted photos</h2>
    <div class="card-deck">
        @isset ($images)
            @foreach ($images as $d)
                <div class="card" style="min-width: 21rem; max-width: 21rem;">
                    <img src="data:image/png;base64,{{ $d->image }}" class="card-img-top" alt="...">
                    <div class="card-body">
                        <blockquote class="blockquote mb-0">
                            <p>{{ $d->caption }}</p>
                            <footer class="blockquote-footer">Posted by <a href="/user?uid={{ $user->id }}">{{ $user->github_id }}</a></footer>
                        </blockquote>
                    </div>

                    <div class="card-body">
                        <a href="/like/list?iid={{ $d->id }}" class="card-link btn btn-outline-primary">
                        {{ App\Model\Like::where('image_id', $d->id)->count() }} users liked
                        </a>

                        @guest
                            <button class="card-link btn btn-primary" disabled> Like </button>
                        @else
                            <form class="d-inline" action="/like" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="iid" value="{{ $d->id }}">
                                <input type="hidden" name="uid" value="{{ $user->id }}">

                                <?php
                                    $row = App\Model\Like::where('image_id', $d->id)
                                                        ->where('user_id', $user->id)
                                                        ->get();
                                    if (count($row) != 0) { 
                                ?>
                                        <button class="card-link btn btn-secondary"> Dismiss </button>
                                <?php
                                    } else {
                                ?>
                                        <button class="card-link btn btn-primary"> Like </button>
                                <?php
                                    }
                                ?>
                            </form>
                        @endguest

                        @guest
                            <button class="card-link btn btn-danger" disabled> Delete </button>
                        @else
                            @if (auth()->user()->id != $user->id)
                            <button class="card-link btn btn-danger" disabled> Delete </button>
                            @else
                                <button class="card-link btn btn-danger" data-toggle="modal" data-target="#exampleModal"> Delete </button>
                                
                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Warning!</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                This post is completely deleted! Are you sure?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <form action="/post/delete" method="post">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="id" value="{{ $d->id }}">
                                                    <button class="card-link btn btn-danger"> Delete </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endguest
                    </div>
                </div>
            @endforeach
        @endisset
    </div>

    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            @if ($pg > 1)
                <li class="page-item disabled">
                    <a class="page-link" href="user?uid={{ $user->id }}&pg={{ $pg - 1 }}" tabindex="-1" aria-disabled="true">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="user?uid={{ $user->id }}&pg={{ $pg - 1 }}">{{ $pg - 1 }}</a>
                </li>
            @else
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            @endif

            <li class="page-item">
                <a class="page-link" href="#">{{ $pg }} <span class="sr-only">(current)</span></a>
            </li>

            @if ($pg < $maxPg)
                <li class="page-item">
                    <a class="page-link" href="user?uid={{ $user->id }}&pg={{ $pg + 1 }}">{{ $pg + 1 }}</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="user?uid={{ $user->id }}&pg={{ $pg + 1 }}">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
</div>
@endsection