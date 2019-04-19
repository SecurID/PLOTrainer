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
                    <ul class="list-group">
                        @foreach ($users as $user)
                            <li class="list-group-item justify-content-between align-items-center d-flex" id="lisituation{{$user->id}}">{{ $user->email }}<button class="btn btn-danger" id="situation{{$user->id}}">Delete</button><input type="checkbox" name="checkAdmin" id="checkAdmin{{$user->id}}" @if($user->admin == 1) checked="checked" @endif></li>
                        @endforeach
                    </ul>
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
    @foreach($users as $user)
    jQuery('#situation{{$user->id}}').click(function (e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        jQuery.ajax({
            url: "{{ url('/deleteUser/'.$user->id) }}",
            method: 'get',
            success: function (result) {
                location.reload();
            }
        });
    });
    @endforeach
    @foreach($users as $user)
    jQuery('#checkAdmin{{$user->id}}').click(function (e) {
        e.preventDefault();
        jQuery.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            url: "{{ url('/changeAdminStatus/'.$user->id) }}",
            method: 'get',
            data: {
                value: jQuery('#checkAdmin{{$user->id}}').prop("checked")
            },
            success: function (result) {
                location.reload();
            }
        });
    });
    @endforeach
});
</script>
@endsection

