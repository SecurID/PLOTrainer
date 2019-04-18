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
                        @foreach ($situations as $situation)
                            <li class="list-group-item justify-content-between align-items-center d-flex" id="lisituation{{$situation->id}}">
                                <input type="text" class="form-control" id="situationName{{$situation->id}}" value="{{ $situation->name }}">
                                <select name="positionSelect" class="form-control" id="positionSelect{{ $situation->id }}">
                                    <option value="utg" @if ($situation->position == "utg") selected @endif>UTG</option>
                                    <option value="mp" @if ($situation->position == "mp") selected @endif>MP</option>
                                    <option value="co" @if ($situation->position == "co") selected @endif>CO</option>
                                    <option value="btn" @if ($situation->position == "btn") selected @endif>BTN</option>
                                    <option value="sb" @if ($situation->position == "sb") selected @endif>SB</option>
                                    <option value="bb" @if ($situation->position == "bb") selected @endif>BB</option>
                                </select>
                                <button class="btn btn-warning" id="situationEdit{{$situation->id}}">Rename</button>
                                <button class="btn btn-danger" id="situation{{$situation->id}}">Delete</button>
                                <input type="checkbox" @if ($situation->onlyAdmin == 1) checked="checked" @endif id="checkSituation{{$situation->id}}">Nur Admin?
                            </li>
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
    @foreach($situations as $situation)
    jQuery('#situation{{$situation->id}}').click(function (e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        jQuery.ajax({
            url: "{{ url('/deleteSituation/'.$situation->id) }}",
            method: 'get',
            success: function (result) {
                location.reload();
            }
        });
    });
    @endforeach
    @foreach($situations as $situation)
    jQuery('#situationEdit{{$situation->id}}').click(function (e) {
        e.preventDefault();
        jQuery.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            url: "{{ url('/renameSituation/'.$situation->id) }}",
            method: 'get',
            data: {
                newName: jQuery('#situationName{{$situation->id}}').val()
            },
            success: function (result) {
                location.reload();
            }
        });
    });
    @endforeach
    @foreach($situations as $situation)
    jQuery('#checkSituation{{$situation->id}}').click(function (e) {
        e.preventDefault();
        jQuery.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            url: "{{ url('/onlyAdminSituation/'.$situation->id) }}",
            method: 'get',
            data: {
                value: jQuery('#checkSituation{{$situation->id}}').prop("checked")
            },
            success: function (result) {
                location.reload();
            }
        });
    });
    @endforeach
    @foreach($situations as $situation)
    jQuery('#positionSelect{{ $situation->id }}').change(function (e) {
        e.preventDefault();
        jQuery.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            url: "{{ url('/changePosition/'.$situation->id) }}",
            method: 'get',
            data:{
                position: jQuery('#positionSelect{{$situation->id}}').val(),
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

