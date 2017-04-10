$(function () {
	var URL = document.baseURI;

	var add_cart_btn = '.js_add_to_cart';
	var add_categ_form = '#add_categ_form';
	var success_block = "#success";
	var cart_block = "#cart";
    var search_btn = "#search_btn";
    var search_form = "#search_form";
    var search_sbmt_btn = "#search";
    var goods_block = "#items";
    var sort_link = ".sort_by";

    // $(search_block).hide();

	// добавление товара в корзину
	// $(add_cart_btn).live("click", function(){
	// 	var item_id = $(this).attr('id');
 //        var item_block = '#'+ item_id;
 //        var item_count = $(item_block).val();
 //        alert(item_count);
 //        return false;
	// 	$.ajax
 //        ({
 //            type: "POST",
 //            url: URL +"shop/main/add/"+ item_id,
 //            // data: $(filter_vorm).serialize(),
 //            //dataString,
 //            cache: false,
 //            success: function(html)
 //            {
 //                $(success_block).html('<div class="alert alert-success">Товар был успешно добавлен в корзину</div>')
 //                $(cart_block).html(html);
 //            },
 //            error:function (xhr, ajaxOptions, thrownError){
 //                console.log(xhr.status);
 //                console.log(thrownError);
 //            }
 //        });
	// 	return false;
	// });


    // $(search_block).hide();

    // добавление товара в корзину
    $(add_cart_btn).click(function(){
        var item_id = $(this).attr('id');
        var item_block = '.'+ item_id;
        var item_count = $(item_block).attr("value");
        $.ajax
        ({
            type: "POST",
            url: URL +"shop/main/add/"+ item_id +"/"+ item_count,
            // data: $(filter_vorm).serialize(),
            //dataString,
            cache: false,
            success: function(html)
            {
                $(success_block).html('<div class="alert alert-success">Товар был успешно добавлен в корзину</div>')
                $(cart_block).html(html);
            },
            error:function (xhr, ajaxOptions, thrownError){
                console.log(xhr.status);
                console.log(thrownError);
            }
        });
        return false;
    });


    // поиск по товарам
    $(search_sbmt_btn).click(function()
    {
        $.ajax
        ({
            type: "POST",
            url: URL +"shop/main/ajax_search",
            data: $(search_form).serialize(),
            //dataString,
            cache: false,
            success: function(html)
            {
                //$(success_block).html('<div class="alert alert-success">Товар был успешно добавлен в корзину</div>')
                $(goods_block).html(html);
            },
            error:function (xhr, ajaxOptions, thrownError){
                console.log(xhr.status);
                console.log(thrownError);
            }
        });
        return false;
    });

    // сортировка
    $(".arr_u").hide();
    $(sort_link).click(function(){
        $(".nav > li").removeClass('active');
        // var curr_sort = $(this).attr('alt');
        $(this).parent().addClass('active');
        $(this).children().toggle();
        $(this).toggleClass('desc');

        var sort_field      = $(this).attr("id");
        var sort_direction  = $(this).attr("class");
        var title           = $(".title").val();
        var article         = $(".article").val();
        var equipment_type  = $(".equipment_type").val();
        var device_type     = $(".device_type").val();
        // alert(equipment_type);

        $.ajax
        ({
            type: "POST",
            url: URL +"shop/main/ajax_sort",
            data: {field: sort_field, direction: sort_direction, title: title, article: article, equipment_type: equipment_type, device_type: device_type},
            //dataString,
            cache: false,
            success: function(html)
            {
                //$(success_block).html('<div class="alert alert-success">Товар был успешно добавлен в корзину</div>')
                $(goods_block).html(html);
            },
            error:function (xhr, ajaxOptions, thrownError){
                console.log(xhr.status);
                console.log(thrownError);
            }
        });

        return false;
    });
});