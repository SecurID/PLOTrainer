@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Situations</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form action="/processFiles" enctype="multipart/form-data" method="GET">
                        <ul class="list-group">
                            @foreach ($situations as $situation)
                                <li class="list-group-item">{{ $situation->name }}<button class="btn btn-danger" id="situation{{$situation->id}}">Delete</button></li>
                            @endforeach
                        </ul>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
