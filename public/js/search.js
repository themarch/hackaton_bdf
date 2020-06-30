
function delay(callback, ms) {
    var timer = 0;
    return function() {
      var context = this, args = arguments;
      clearTimeout(timer);
      timer = setTimeout(function () {
        callback.apply(context, args);
      }, ms || 0);
    };
  }

$(document).ready(function(){
    $('#search1').keyup(delay(function() {
        etablissement = $('#search2').val();
        competence = $('#search3').val();
        var name = $(this).val();
        if (name.length == 0 && etablissement.length == 0 && competence.length == 0) {
            $('#result').html('');
            $('#error').css('display', 'none');
            $('#result').css('display', 'none');
        }
        else {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:'POST',
                url: "search1",
                data: { name: name, etablissement:etablissement, competence:competence},
                dataType:'text',
                success:function(data) {
                    if (data == "rip") {
                        $('#result').css('display', 'none');
                        $('#error').css('display', 'block');
                        $('#error').html('<p> Personne ne peut-être trouvé </p>');
                        return ;
                    }
                    $('#error').css('display', 'none');
                    $('#result').css('display', 'block');
                    $('#result').html(data);
                    size = $('.round').length;
                    if (size >= 5) {
                        $('#result').css('overflow-y', 'scroll');
                    }
                },
            });
        }
    }, 300));
})

$(document).ready(function(){
    $('#search2').keyup(delay(function() {
        name = $('#search1').val();
        competence = $('#search3').val();
        var etablissement = $(this).val();
        if (name.length == 0 && etablissement.length == 0 && competence.length == 0) {
            $('#result').html('');
            $('#error').css('display', 'none');
            $('#result').css('display', 'none');
        }
        else {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:'POST',
                url: "search2",
                data: { name: name, etablissement:etablissement, competence:competence},
                dataType:'text',
                success:function(data) {
                    if (data == "rip") {
                        $('#result').css('display', 'none');
                        $('#error').css('display', 'block');
                        $('#error').html('<p> Personne ne peut-être trouvé </p>');
                        return ;
                    }
                    $('#error').css('display', 'none');
                    $('#result').css('display', 'block');
                    $('#result').html(data);
                    size = $('.round').length;
                    if (size >= 5) {
                        $('#result').css('overflow-y', 'scroll');
                    }
                },
            });
        }
    }, 300));
})

$(document).ready(function(){
    $('#search3').keyup(delay(function() {
        name = $('#search1').val();
        etablissement = $('#search2').val();
        var competence = $(this).val();
        if (name.length == 0 && etablissement.length == 0 && competence.length == 0) {
            $('#result').html('');
            $('#error').css('display', 'none');
            $('#result').css('display', 'none');
        }
        else {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:'POST',
                url: "search3",
                data: { name: name, etablissement:etablissement, competence:competence},
                dataType:'text',
                success:function(data) {
                    if (data == "rip") {
                        $('#result').css('display', 'none');
                        $('#error').css('display', 'block');
                        $('#error').html('<p> Personne ne peut-être trouvé </p>');
                        return ;
                    }
                    $('#error').css('display', 'none');
                    $('#result').css('display', 'block');
                    $('#result').html(data);
                    size = $('.round').length;
                    if (size >= 5) {
                        $('#result').css('overflow-y', 'scroll');
                    }
                },
            });
        }
    }, 300));
})
