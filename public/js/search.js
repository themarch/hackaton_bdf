
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
    $('#search').keyup(delay(function() {
        if ($(this).val().length == 0) {
            $('#result').html('');
        }
        if ($(this).val().length >= 1) {
        var txt = $(this).val();
        if (txt != '') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:'POST',
                url: "search",
                data: { txt: txt},
                dataType:'text',
                success:function(data) {
                    $('#result').html(data);
                },
            });
        }
        else {
            $('#result').html('');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:'POST',
                url: "search",
                data: { txt: txt},
                dataType:'text',
                success:function(data) {
                    $('#result').html(data);
                },
            });
        }
    }
    }, 500));
})

/*
$(document).ready(function(){
    
    fetch_customer_data();

    function fetch_customer_data(query = '')
    {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:'POST',
            url: "search",
            data: { query: query},
            dataType:'json',
            success:function(data) {
                if (data[0].name) {
                    console.log(data[0].name)
                }
            },
        });
    }
    $(document).on('keyup', '#search', function(){
        var query = $(this).val();
        fetch_customer_data(query);
    });
});*/