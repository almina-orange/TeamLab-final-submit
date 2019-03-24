@extends('../../layouts/my')
@section('title', 'Post')

<!-- Script for preview post image
Ref :: http://www.koikikukan.com/archives/2016/07/06-000300.php -->
<script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>
<script>
$(function(){
    $('#file').change(function(){
        $('img').remove();
        var file = $(this).prop('files')[0];
        if(!file.type.match('image.*')){
            return;
        }
        var fileReader = new FileReader();
        fileReader.onloadend = function() {
            $('#result').html('<img src="' + fileReader.result + '" height="250"/>');
        }
        fileReader.readAsDataURL(file);
    });
});
</script>

@section('content')
<!-- Error message -->
@if ($errors->any())
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Warning!</strong> You should check in on some of those fields below.
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<div class="container">
    <h2>Post form</h2>
    <!-- Form -->
    <form action="{{ url('post/upload') }}" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <p>User : {{ auth()->user()->github_id }}</p>
            <input type="hidden" name="uid" value="{{ auth()->user()->id }}">
        </div>

        <div class="form-group">
            <label for="photo">Imagefile</label>
            <input type="file" class="form-control-file" name="file" id="file">
            <div id="result"></div>  <!-- Preview -->
        </div>
            
        <div class="form-group">
            <label for="caption">Caption</label>
            <textarea class="form-control" name="caption" rows="4" cols="40"></textarea>
        </div>
        
        {{ csrf_field() }}
        <button class="btn btn-success"> Upload </button>
    </form>
    </div>
</div>
@endsection