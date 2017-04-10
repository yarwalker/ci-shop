/*(function($) {
//$(function(){
    $('.button').click(function(){
        //alert($(this).parent('form').(':hidden[name = cnt]').val());
        alert('clicked');
        console.log('clicked');
    });
})(jQuery); */

// отображение обновленной корзины
/*function showCartTable() {
    
}*/

$(function(){
    $('#st').focus(function(){
        $(this).val('');
    });
    
    $('#st').blur(function(){
        var dv = this.defaultValue;
        
        //console.log(dv);
        if(this.value == '') {this.value = dv;}
        //console.log($(this).val());
    });
    /*
    $('#left_menu > ul li').toggle(function(){
           $(this).children('ul').slideDown(400);
           }, 
        function(){
           $(this).children('ul').slideUp(400);  
    });*/
    
    //$('#left_menu li ul').hide();
    
    $('ul#left_menu > li > span').click(function(event) {
        var this_el = $(this);
        var lnk = this_el.children('a');
        
        $('ul#left_menu li > span').removeClass('selected'); //css({'background':'none'});
        $('ul#left_menu li ul[class *= visible]').removeClass('visible').slideUp(400);
        $('ul#left_menu li a[class *= selected]')
            .removeClass('root-item-selected')
            .addClass('root-item')
            .css({'color':'#0091D5'});
        
        this_el.addClass('selected');//css({'background':'url("/images/act_lm_bg.png") no-repeat'});
        lnk.css({'color':'#fff'});
        if(this_el.next('ul').size() != 0) event.preventDefault();
        
        //if(this_el.next('ul[class *= visible]').size() != 0) {
        if(this_el.next('ul').is(':visible')) { 
            this_el.removeClass('selected');//css({'background':'none'});
            lnk.css({'color':'#0091D5'})
                .removeClass('root-item-selected')
                .addClass('root-item');
            this_el.next('ul').removeClass('visible').slideUp(400);
        } else {
            
            // получаем подменю
            $.ajax({

            });

            this_el.next('ul').addClass('visible').slideDown(400);
            
            lnk.removeClass('root-item').addClass('root-item-selected');
        }
   });

   $('ul#left_menu li span a').on('click', function(ev){
       var $span = $(this).closest('span');

       ev.preventDefault();

       if( $span.next('ul').length == 0 ) {
           location.href = $(this).attr('href');
       }


   });
    
    
  // $('ul#left_menu a[class *= selected]').closest('ul').css({'display':'block'}).addClass('visible');
  // $('ul[class *= visible]').prev('a.root-item').removeClass('root-item').addClass('root-item-selected');
    
   /* $('#left_menu > ul li a').on('click',function(eventObject){
        eventObject.preventDefault();
    });*/
    
    
  var name = $( "#name" ),
      email = $( "#email" ),
      org_name = $( "#org_name" ),
      resp_text = $( "#resp_text" ),
      allFields = $( [] ).add( name ).add( email ).add( org_name ).add( resp_text ),
      tips = $( ".validateTips" );
 
    function updateTips( t ) {
      tips
        .text( t )
        .addClass( "ui-state-highlight" );
      setTimeout(function() {
        tips.removeClass( "ui-state-highlight", 1500 );
      }, 500 );
    }
 
    function checkLength( o, n, min, max ) {
      if ( o.val().length > max || o.val().length < min ) {
        o.addClass( "ui-state-error" );
        updateTips( "Длина '" + n + "' должна быть между " +
          min + " и " + max + "." );
        return false;
      } else {
        return true;
      }
    }
 
    function checkRegexp( o, regexp, n ) {
      if ( !( regexp.test( o.val() ) ) ) {
        o.addClass( "ui-state-error" );
        updateTips( n );
        return false;
      } else {
        return true;
      }
    }
 
    $( "#dialog-form" ).dialog({
      autoOpen: false,
      height: 530,
      width: 500,
      modal: true,
      show: "blind",
      hide: "explode",
      buttons: {
        "Оставить отзыв": function() {
          var bValid = true;
          allFields.removeClass( "ui-state-error" );
 
          bValid = bValid && checkLength( name, "ФИО", 3, 100 );
          bValid = bValid && checkLength( email, "Email", 6, 100 );
          bValid = bValid && checkLength( org_name, "Организация", 3, 100 );
          bValid = bValid && checkLength( resp_text, "Отзыв", 5, 500 );
 
          bValid = bValid && checkRegexp( name, /^[а-яА-Я]([а-яА-Я\s])+$/, "ФИО может состоять только из кириллических букв." );
          // From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
          bValid = bValid && checkRegexp( email, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "Не верный формат e-mail адреса т.е. ui@jquery.com" );
          bValid = bValid && checkRegexp( org_name, /^([0-9a-zA-Zа-яА-Я\"\-\s])+$/, "В названии организации возможны только следующие символы: а-я a-z 0-9" );
          bValid = bValid && checkRegexp( resp_text, /^([0-9a-zA-Zа-яА-Я\s])+$/, "В отзыве возможны только следующие символы: а-я a-z 0-9" );
 
          if ( bValid ) {
            $( "#users tbody" ).append( "<tr>" +
              "<td>" + name.val() + "</td>" +
              "<td>" + email.val() + "</td>" +
              "<td>" + org_name.val() + "</td>" +
              "<td>" + resp_text.val() + "</td>" +
            "</tr>" );
            
            // отправка письма с отзывом для модерации
            $.ajax({
                 type: "POST",
                 url: "/content/responses/send_response.php",
                 dataType: "html",
                 data: {fio: name.val(),
                        email: email.val(),
                        org_name: org_name.val(),
                        resp_text: resp_text.val()
                 },
                 success: function(data) {
                            // скрываем блок с сообщением после обработки обработки
                            //$('#wait-window').hide();
                            // выводим лог работы в блок
                            //$('#logs').html(data);
                            alert(data);
                                                 
                     },
                 error: function(response) {
                     // скрываем блок с сообщением после обработки обработки
                     //$('#wait-window').hide();
                     alert('error: ' + response.responseText);
                 }
             });
            
            $( this ).dialog( "close" );
          } 
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      },
      close: function() {
        allFields.val( "" ).removeClass( "ui-state-error" );
      }
    });
 
    $( "#cr_response, .resp_lnk" )
      //.button()
      .click(function() {
        $( "#dialog-form" ).dialog( "open" );
      });

    $('button.add2cart').on('click', function(){
        //console.log('click');

        $.ajax({
            type: "POST",
            url: '/catalog/addToCart',
            dataType: "json",
            data: {
                quantity: $('#quantity').val(),
                item_id: $('#item_id').val(),
                item_name: $('#item_name').val(),
                item_price: $('#item_price').val()
            },
            success: function(data) {
                if( data.rowid != -1 )
                    alert('Товар успешно добавлен в корзину!');
                else
                    alert('При добавлении товара произошла ошибка!');
            },
            error: function(response) {
                alert('error: ' + response.responseText);
            }
        });
    });
});