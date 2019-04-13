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
                        <form action="/optionSituationsTraining" enctype="multipart/form-data" method="GET">
                            <div class="form-row align-items-center d-flex justify-content-center">
                                <div class="col-auto">
                                    <label for="inputFold">Min. Fold Percentage</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">%</div>
                                        </div>
                                        <input type="number" class="form-control" id="inputFold" placeholder="20" min="0" max="100" required>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <label for="inputCall">Min. Call Percentage</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">%</div>
                                        </div>
                                        <input type="number" class="form-control" id="inputCall" placeholder="20" min="0" max="100" required>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <label for="inputRaise">Min. Raise Percentage</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">%</div>
                                        </div>
                                        <input type="number" class="form-control" id="inputRaise" placeholder="20" min="0" max="100" required>
                                    </div>
                                </div>
                            </div>
                        <div class="m-3 d-flex justify-content-center"><button class="btn btn-primary" id="startTraining">Start Training</button>
                            {{ csrf_field() }}</div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
