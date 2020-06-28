
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
        var competence = $(this).val();
        var id = $('.id1').text();
        if (competence.length == 0) {
            $('.result').css('display', 'none');
            $('.all_text').css('display', 'block');
        }
        else {
            $('.result').css('display', 'block');
            $('.all_text').css('display', 'none');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:'POST',
                url: "/profilesearch",
                data: { competence: competence, id: id},
                dataType:'text',
                success:function(data) {
                    $('.result').html(data);
                },
            });
        }
    }, 500));
})