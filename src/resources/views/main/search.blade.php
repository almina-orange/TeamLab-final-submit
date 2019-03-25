@extends('../../layouts/my')
@section('title', 'Home')
@section('content')
<!-- View uploaded image -->
<div class='container'>
    <h2>Search by "{{ $target }}".</h2>
    <div class="card-deck">
        @isset ($images)
            @foreach ($images as $d)
                <div class="card" style="min-width: 21rem; max-width: 21rem;">
                    <img src="data:image/png;base64,{{ $d->image }}" class="card-img-top" alt="...">
                    <div class="card-body">
                        <blockquote class="blockquote mb-0">
                            <p>{{ $d->caption }}</p>
                            <footer class="blockquote-footer">
                            Posted by <a href="/user?uid={{ $d->user_id }}">{{ App\User::where('id', $d->user_id)->first()->github_id }} </a>
                            </footer>
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
                                <input type="hidden" name="uid" value="{{ auth()->user()->id }}">

                                <?php
                                    $row = App\Model\Like::where('image_id', $d->id)
                                                        ->where('user_id', auth()->user()->id)
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
                    </div>
                </div>
            @endforeach
        @endisset
    </div>
</div>

<div class="container">
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            @if ($pg > 1)
                <li class="page-item">
                    <a class="page-link" href="search?word={{ $target }}&pg={{ $pg - 1 }}">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="search?word={{ $target }}&pg={{ $pg - 1 }}">{{ $pg - 1 }}</a>
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
                    <a class="page-link" href="search?word={{ $target }}&pg={{ $pg + 1 }}">{{ $pg + 1 }}</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="search?word={{ $target }}&pg={{ $pg + 1 }}">
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