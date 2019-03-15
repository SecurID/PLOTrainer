@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Random Situations</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form id="startTrainingForm" action="" enctype="multipart/form-data" method="POST">
                        <div class="d-flex justify-content-center"><button class="btn btn-primary" id="startTraining">Start Training</button>
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
                            <button class="btn btn-danger" id="fold">Fold</button>
                            <p class="d-none" id="labelFold"></p>
                            <button class="btn btn-warning"id="call">Call</button>
                            <p class="d-none" id="labelCall"></p>
                            <button class="btn btn-success"id="raise">Raise</button>
                            <p class="d-none" id="labelRaise"></p>
                        </div>
                        <div class="d-flex justify-content-center" style="margin-top: 50px;">
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
                url: "{{ url('/getHand') }}",
                method: 'get',
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
        jQuery('#next').click(function (e) {
            e.preventDefault();

            $('#labelFold').addClass('d-none');
            $('#labelCall').addClass('d-none');
            $('#labelRaise').addClass('d-none');
            $('#next').addClass('d-none');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            jQuery.ajax({
                url: "{{ url('/getHand') }}",
                method: 'get',
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
        jQuery('#fold').click(function (e) {
            var correct = false;

            e.preventDefault();
            $('#labelFold').removeClass('d-none');
            $('#labelCall').removeClass('d-none');
            $('#labelRaise').removeClass('d-none');

            if(document.getElementById("labelFold").innerHTML >= document.getElementById("labelRaise").innerHTML && document.getElementById("labelFold").innerHTML >= document.getElementById("labelCall").innerHTML){
                correct = true;
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
                    $('#next').removeClass('d-none');
                }
            });
        });
        jQuery('#raise').click(function (e) {
            var correct = false;

            if(document.getElementById("labelRaise").innerHTML >= document.getElementById("labelFold").innerHTML && document.getElementById("labelRaise").innerHTML >=document.getElementById("labelCall").innerHTML){
                correct = true;
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
                    $('#next').removeClass('d-none');
                }
            });
        });
        jQuery('#call').click(function (e) {
            var correct = false;

            if(document.getElementById("labelCall").innerHTML >= document.getElementById("labelFold").innerHTML && document.getElementById("labelCall").innerHTML >= document.getElementById("labelRaise").innerHTML){
                correct = true;
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
                    $('#next').removeClass('d-none');
                }
            });
        });
    })

</script>
@endsection
