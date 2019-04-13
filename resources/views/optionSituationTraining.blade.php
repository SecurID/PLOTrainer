@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Selected Situations</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form id="startTrainingForm" action="" enctype="multipart/form-data" method="POST">
                        <div class="m-3 d-flex justify-content-center"><button class="btn btn-primary" id="startTraining">Start Training</button>
                            {{ csrf_field() }}</div>
                    </form>
                    <div class="d-none" id="showTask">
                        <div class="d-flex justify-content-center" style="margin-top: 50px;">
                            <div id="situationName"></div>
                        </div>
                        <div class="d-flex justify-content-center" style="margin-top: 50px;">
                            <div><img id="card1" src="" width="50px" height="100px" style="float:left;"></div>
                            <div><img id="card2" src="" width="50px" height="100px" style="float:left;"></div>
                            <div><img id="card3" src="" width="50px" height="100px" style="float:left;"></div>
                            <div><img id="card4" src="" width="50px" height="100px" ></div>
                        </div>
                        <div class="d-flex justify-content-center" style="margin-top: 50px;">
                            <button class="m-2 btn btn-danger btn-lg" id="fold">Fold</button>
                            <button class="m-2 btn btn-warning btn-lg"id="call">Call</button>
                            <button class="m-2 btn btn-success btn-lg"id="raise">Raise</button>
                        </div>
                        <div class="d-flex justify-content-center">
                            <table style="border: none">
                                <tr>
                                    <td class="text-center" width="100px"><p class="d-none" id="labelFold"></p></td>
                                    <td class="text-center" width="100px"><p class="d-none" id="labelCall"></p></td>
                                    <td class="text-center" width="100px"><p class="d-none" id="labelRaise"></p></td>
                                </tr>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            <p id="info" class="display-4"></p>
                        </div>
                        <div class="m-3 d-flex justify-content-center" style="margin-top: 50px;">
                            <button class="btn btn-primary d-none" id="next">Next</button>
                        </div>
                    </div>
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
    var user_id = {{ $user_id }};
    jQuery(document).ready(function() {
        jQuery('#startTraining').click(function (e) {
            $('#startTrainingForm').addClass('d-none');
            e.preventDefault();
            $('#showTask').removeClass('d-none');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            jQuery.ajax({
                url: "{{ url('/getHandOptions') }}",
                method: 'get',
                data: {
                    FoldPercentage: {{$foldPercentage}},
                    CallPercentage: {{$callPercentage}},
                    RaisePercentage: {{$raisePercentage}},
                },
                success: function (result) {
                    document.getElementById("card1").src = "{{ asset('assets/cards/') }}" + '/' + result.cardOne + '.png';
                    document.getElementById("card2").src = "{{ asset('assets/cards/') }}" + '/' + result.cardTwo + '.png';
                    document.getElementById("card3").src = "{{ asset('assets/cards/') }}" + '/' + result.cardThree + '.png';
                    document.getElementById("card4").src = "{{ asset('assets/cards/') }}" + '/' + result.cardFour + '.png';
                    document.getElementById("situationName").innerHTML = result.situationName;
                    document.getElementById("labelFold").innerHTML = result.foldPercentage+'%';
                    document.getElementById("labelCall").innerHTML = result.callPercentage+'%';
                    document.getElementById("labelRaise").innerHTML = result.raisePercentage+'%';
                }
            });
        });
        function newCards () {
            document.getElementById("card1").src = "{{ asset('assets/cards/back.png') }}";
            document.getElementById("card2").src = "{{ asset('assets/cards/back.png') }}";
            document.getElementById("card3").src = "{{ asset('assets/cards/back.png') }}";
            document.getElementById("card4").src = "{{ asset('assets/cards/back.png') }}";
            document.getElementById("situationName").innerHTML = 'Loading ...';

            $('#labelFold').addClass('d-none');
            $('#labelCall').addClass('d-none');
            $('#labelRaise').addClass('d-none');
            $('#next').addClass('d-none');
            document.getElementById("call").disabled = false;
            document.getElementById("raise").disabled = false;
            document.getElementById("fold").disabled = false;
            $("#raise").removeClass('active');
            $("#fold").removeClass('active');
            $("#call").removeClass('active');
            $('#info').html('');


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            jQuery.ajax({
                url: "{{ url('/getHandSituations') }}",
                method: 'get',
                data: {
                    FoldPercentage: {{$foldPercentage}},
                    CallPercentage: {{$callPercentage}},
                    RaisePercentage: {{$raisePercentage}},
                },
                success: function (result) {
                    document.getElementById("card1").src = "{{ asset('assets/cards/') }}" + '/' + result.cardOne + '.png';
                    document.getElementById("card2").src = "{{ asset('assets/cards/') }}" + '/' + result.cardTwo + '.png';
                    document.getElementById("card3").src = "{{ asset('assets/cards/') }}" + '/' + result.cardThree + '.png';
                    document.getElementById("card4").src = "{{ asset('assets/cards/') }}" + '/' + result.cardFour + '.png';
                    document.getElementById("situationName").innerHTML = result.situationName;
                    document.getElementById("labelFold").innerHTML = result.foldPercentage+'%';
                    document.getElementById("labelCall").innerHTML = result.callPercentage+'%';
                    document.getElementById("labelRaise").innerHTML = result.raisePercentage+'%';
                }
            });
        };
        jQuery('#fold').click(function (e) {
            var correct = false;

            e.preventDefault();
            $('#labelFold').removeClass('d-none');
            $('#labelCall').removeClass('d-none');
            $('#labelRaise').removeClass('d-none');
            document.getElementById("raise").disabled = true;
            document.getElementById("call").disabled = true;
            document.getElementById("fold").disabled = true;

            if(document.getElementById("labelFold").innerHTML >= document.getElementById("labelRaise").innerHTML && document.getElementById("labelFold").innerHTML >= document.getElementById("labelCall").innerHTML){
                correct = true;
                $('#info').html('Correct');
                $('#info').css('color', 'green');
                $("#fold").css('opacity', '1');

            }else{
                if(document.getElementById("labelCall").innerHTML >= document.getElementById("labelRaise").innerHTML){
                    $("#call").css('opacity', '1');
                    $('#info').html('Wrong');
                    $('#info').css('color', 'red');
                }
                if(document.getElementById("labelRaise").innerHTML >= document.getElementById("labelCall").innerHTML){
                    $("#raise").css('opacity', '1');
                    $('#info').html('Wrong');
                    $('#info').css('color', 'red');
                }
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            jQuery.ajax({
                url: "{{ url('/api/answer') }}",
                method: 'post',
                data: {
                    situation_id: document.getElementById("situationName").innerHTML,
                    user_id: user_id,
                    correct: correct
                },
                success: function (result) {
                    sleep(2000);
                    newCards();
                }
            });
        });
        jQuery('#raise').click(function (e) {
            var correct = false;

            e.preventDefault();
            $('#labelFold').removeClass('d-none');
            $('#labelCall').removeClass('d-none');
            $('#labelRaise').removeClass('d-none');
            document.getElementById("raise").disabled = true;
            document.getElementById("call").disabled = true;
            document.getElementById("fold").disabled = true;

            if(document.getElementById("labelRaise").innerHTML >= document.getElementById("labelFold").innerHTML && document.getElementById("labelRaise").innerHTML >=document.getElementById("labelCall").innerHTML){
                correct = true;
                $('#info').html('Correct');
                $('#info').css('color', 'green');
                $("#raise").css('opacity', '1');
            }else{
                if(document.getElementById("labelCall").innerHTML >= document.getElementById("labelFold").innerHTML){
                    $("#call").css('opacity', '1');
                    $('#info').html('Wrong');
                    $('#info').css('color', 'red');
                }
                if(document.getElementById("labelFold").innerHTML >= document.getElementById("labelCall").innerHTML){
                    $("#fold").css('opacity', '1');
                    $('#info').html('Wrong');
                    $('#info').css('color', 'red');
                }
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            jQuery.ajax({
                url: "{{ url('/api/answer') }}",
                method: 'post',
                data: {
                    situation_id: document.getElementById("situationName").innerHTML,
                    user_id: user_id,
                    correct: correct
                },
                success: function (result) {
                    sleep(2000);
                    newCards();
                }
            });
        });
        jQuery('#call').click(function (e) {
            var correct = false;

            document.getElementById("raise").disabled = true;
            document.getElementById("call").disabled = true;
            document.getElementById("fold").disabled = true;

            if(document.getElementById("labelCall").innerHTML >= document.getElementById("labelFold").innerHTML && document.getElementById("labelCall").innerHTML >= document.getElementById("labelRaise").innerHTML){
                correct = true;
                $('#info').html('Correct');
                $('#info').css('color', 'green');
                $("#call").css('opacity', '1');
            }else{
                if(document.getElementById("labelRaise").innerHTML >= document.getElementById("labelFold").innerHTML){
                    $("#call").css('opacity', '1');
                    $('#info').html('Wrong');
                    $('#info').css('color', 'red');
                }
                if(document.getElementById("labelFold").innerHTML >= document.getElementById("labelRaise").innerHTML){
                    $("#fold").css('opacity', '1');
                    $('#info').html('Wrong');
                    $('#info').css('color', 'red');
                }
            }

            e.preventDefault();
            $('#labelFold').removeClass('d-none');
            $('#labelCall').removeClass('d-none');
            $('#labelRaise').removeClass('d-none');


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            jQuery.ajax({
                url: "{{ url('/api/answer') }}",
                method: 'post',
                data: {
                    situation_id: document.getElementById("situationName").innerHTML,
                    user_id: user_id,
                    correct: correct
                },
                success: function (result) {
                    sleep(2000);
                    newCards();
                }
            });
        });
        function sleep(milliseconds) {
            var start = new Date().getTime();
            for (var i = 0; i < 1e7; i++) {
                if ((new Date().getTime() - start) > milliseconds){
                    break;
                }
            }
        }
    })

</script>
@endsection
