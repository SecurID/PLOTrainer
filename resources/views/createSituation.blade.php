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

                        <form action="/processFiles" enctype="multipart/form-data" method="POST">
                            <p>
                                Situation
                                <label for="name">
                                    <input type="text" name="name" id="name">
                                </label>
                            </p>
                            <p>
                                <label for="file">
                                    <input type="file" name="range" id="range">
                                </label>
                            </p>
                            <button>Hochladen</button>
                            {{ csrf_field() }}
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
