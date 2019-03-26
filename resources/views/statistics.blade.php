@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Statistics</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Situation</th>
                                <th scope="col">Correct Answers</th>
                                <th scope="col"># Answers</th>
                                <th scope="col">Last Answer</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($situations as $situation)
                            <tr>
                                <th scope="row">{{$loop->index + 1}}</th>
                                <td>{{$situation['name']}}</td>
                                <td>{{$situation['correctAnswers']}}%</td>
                                <td>{{$situation['sumAnswers']}}</td>
                                @if($situation['lastAnswer'] == 'No Answer')
                                    <td>{{$situation['lastAnswer']}}</td>
                                @else
                                    <td>{{Carbon\Carbon::parse($situation['lastAnswer'])->format('d.m.Y')}}</td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
