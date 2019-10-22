@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <create-post />
        </div>
        <div class="col-md-8 posts-container" style="height:35rem;overflow-y:scroll">
        <all-posts />
        </div>
    </div>
</div>
@endsection
