$('.btn-save').click(function (){
    let name    = $('#name').val();
    let surname = $('#surname').val();
    let age     = $('#age').val();
    let msg     = '';

    $('input').removeClass('border-danger');

    if(!Number(age) || age < 1){
        $('#age').addClass('border-danger')
        msg = "Неправильный формат";
    }

    if(surname.length < 2 || surname.search(/\d/) != -1 ){
        $('#surname').addClass('border-danger')
        msg = "Неправильный формат";
    }
    if(name.length < 2 || name.search(/\d/) != -1 ){
        $('#name').addClass('border-danger')
        msg = "Неправильный формат";
    }

    if(msg != ''){
        $('.msg').html('<div class="alert alert-danger" role="alert">'+msg+'</div>')
    }else{
        $.ajax({
            type: 'POST',
            url: 'app.php',
            data: {create:'create',name:name,surname:surname, age:age},
            success: function(data){
                let res = JSON.parse(data)
                if(res.success){
                    $('.msg').html('<div class="alert alert-success" role="alert">'+res.success+ '</div>');
                    $('input').val('')
                }else{
                    $('.msg').html('<div class="alert alert-danger" role="alert">'+res.error+ '</div>');
                }
            }
        });
    }
})


$('.btn-import').click(function (){
    $.ajax({
        type: 'POST',
        url: 'app.php',
        data: {import:'import'},
        success: function(data){
           console.log(data)
            $('.msg').html('<div class="alert alert-success" role="alert">'+data+ '</div>');
        }
    });
})
