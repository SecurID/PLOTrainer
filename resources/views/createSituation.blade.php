@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create Situations</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @isset($success)
                        <div class="alert alert-success" role="alert">
                            {{ $success }}
                        </div>
                    @endif

                        <form action="/processFiles" enctype="multipart/form-data" method="POST">
                            <p>
                                Situation
                                <label for="name">
                                    <input type="text" class="form-control" name="name" id="name">
                                </label>
                            </p>
                            <p>
                                <label for="rangeRaise"> Raise
                                    <input type="file" class="form-control-file" name="rangeRaise" id="rangeRaise">
                                </label>
                            </p>
                            <p>
                                <label for="rangeCall"> Call
                                    <input type="file" class="form-control-file" name="rangeCall" id="rangeCall">
                                </label>
                            </p>
                            <p>
                                <label for="rangeFold"> Fold
                                    <input type="file" class="form-control-file" name="rangeFold" id="rangeFold">
                                </label>
                            </p>
                            <button class="btn btn-primary btn-lg">Hochladen</button>
                            {{ csrf_field() }}
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
