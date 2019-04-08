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
                    <div class="form-group">
                        <select class="form-control" id="selectUser">
                            <option value="">Please choose...</option>
                            @foreach($users as $user)
                            <option value="{{$user->id}}">{{$user->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <table class="table table-responsive table-hover" id="statisticsTable">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Situation</th>
                                <th scope="col">Correct Answers</th>
                                <th scope="col"># Answers</th>
                                <th scope="col">Last Answer</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyuser">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="http://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous">
</script>
<script>
    jQuery(document).ready(function() {
        jQuery('#selectUser').change(function (e) {
            e.preventDefault();
            $("#tbodyuser").empty();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            jQuery.ajax({
                url: "{{ url('/userStatistics') }}/"+$('#selectUser').val(),
                method: 'get',
                success: function (result) {
                    var i = 1;
                    result.situations.forEach(function(element){
                        $('#statisticsTable').append('<tr><td>'+i+'</td><td>'+element.name+'</td><td>'+element.correctAnswers+'%</td><td>'+element.sumAnswers+'</td><td>'+element.lastAnswer+'</td></tr>')
                        i++;
                    })
                }
            });
        });
    })
</script>
@endsection
