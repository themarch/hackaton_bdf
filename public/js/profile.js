
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
    var size = $(window).height();
    var div_left = $('.left').height();
    if (div_left > size) {
        $('.article_div').css('height', div_left - 270)
    }
    else {
        $('.article_div').css('height', size - 270)
    }
  })

  $(window).resize(function(){
    var size = $(window).height();
    console.log(size)
    var div_left = $('.left').height();
    console.log(div_left)
    if (div_left > size) {
        $('.article_div').css('height', div_left - 270)
    }
    else {
        $('.article_div').css('height', size - 270)
    }
  })

$(document).ready(function(){
    $('#str1').click(function() {
        $('#str').css('display', 'none');
        $('#str1').css('display', 'none');
        $('#str11').css('display', 'block');
        $('#str_1').css('display', 'block');
    })
})

$(document).ready(function(){
    $('#str_1').click(function() {
        $('#str').css('display', 'block');
        $('#str1').css('display', 'block');
        $('#str11').css('display', 'none');
        $('#str_1').css('display', 'none');
    })
})

$(document).ready(function(){
    $('.exit').click(function() {
        $('#str').css('display', 'block');
        $('#str1').css('display', 'block');
        $('#str11').css('display', 'none');
        $('#str_1').css('display', 'none');
    })
})

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