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
                        <form action="/selectSituationsTraining" enctype="multipart/form-data" method="GET">
                        <ul class="list-group">
                            @foreach ($situations as $situation)
                                <li class="list-group-item">
                                    <div class="form-check">
                                        <input class="form-check-input" name="situation[]" type="checkbox" value="{{$situation->id}}" id="situation{{$situation->id}}">
                                        <label class="form-check-label" for="situation{{$situation->id}}">
                                            {{ $situation->name }}
                                        </label>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="m-3 d-flex justify-content-center"><button class="btn btn-primary" id="startTraining">Start Training</button>
                            {{ csrf_field() }}</div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
