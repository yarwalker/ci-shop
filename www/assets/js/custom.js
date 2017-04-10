function getCookie(c_name)
 {
 var i,x,y,ARRcookies=document.cookie.split(";");
 for (i=0;i<ARRcookies.length;i++)
   {
   x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
   y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
   x=x.replace(/^\s+|\s+$/g,"");
   if (x==c_name)
     {
     return unescape(y);
     }
   }
 }

 function setCookie(c_name,value,exdays)
 {
 var exdate=new Date();
 exdate.setDate(exdate.getDate() + exdays);
 var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
 document.cookie=c_name + "=" + c_value;
 }

 function checkCookie()
 {
 var username=getCookie("username");
 if (username!=null && username!="")
   {
   alert("Welcome again " + username);
   }
 else 
   {
   username=prompt("Please enter your name:","");
   if (username!=null && username!="")
     {
     setCookie("username",username,365);
     }
   }
 }

$(function(){


	$('body').tooltip({
		selector: "[rel=tooltip]",
		placement: "bottom" 
	});
	
	$('.remember_fields').submit(function(event){
		var element = document.getElementById('myTextArea');
		element.onkeyup = function(){
		var val = element.value;
		setCookie("user1",val,17);//Вместо user1 вставьте любой идентификатор, уникальный для данного пользователя и текстового поля. Как вы его добудете - уже не мои проблемы. =) Кука живет в данном случае 17 дней.
		}
	});
	
	$('#captcha a').click(function(event){
		event.returnValue = false;
		if(event.preventDefault) event.preventDefault();
		var lastslash  = window.location.href.lastIndexOf('/') + 1;
		var controller = window.location.href.substr(0,lastslash); 
		var $this	   = $(this);
		
		$.post( controller + 'captcha_refresh', function(data) {
			obData = $.parseJSON( data );
			if (typeof(obData.captchaimage) != "undefined")
			{
				$this.siblings('img').attr("src",obData.captchaimage);
			}
		});
		return false;
	});
	
	
	$('.removeitem').click(function(event){
		event.returnValue = false;
		if(event.preventDefault) event.preventDefault();
		var $this = $(this);
		var link  = $this.attr('href');

		var agree=confirm($this.attr('title') + " ?");
		if (agree)
		{
			$.post( link, function(data) {
				obData = $.parseJSON( data );
				if (obData.result == "ok")
				{
					$this.hide().prev().hide();
				}
			});
		}
		else
		{
			return false ;
		}
	});





});

function confirmMessage( message )
{
	if ( typeof(message) == "undefined" || message.length == '' )
		message = "Are you sure you want to delete?";
		
	var agree = confirm(message);
	if (agree)
		return true ;
	else
	{
		return false ;
	}
}

function markAllRows(tableId)
{
	var obForm = document.getElementById(tableId); 
	var obInputs = obForm.getElementsByTagName('input');

	for (var i = 0; i < obInputs.length; i++) 
	{
		if (obInputs[i].type == "checkbox")
		{
			obInputs[i].checked = 'checked';
		}
	}
	return true;
}

function unMarkAllRows(tableId)
{
	var obForm = document.getElementById(tableId); 
	var obInputs = obForm.getElementsByTagName('input'); 

	for (var i = 0; i < obInputs.length; i++) 
	{
		if (obInputs[i].type == "checkbox" && obInputs[i].checked == true)
		{
			obInputs[i].checked = '';
		}
	}
	return true;
}


function get_in_modal (method, controller, query)
{
	console.log('controller');
	console.log(controller);
	method = 'aj_' + method;
	controller = ((controller != '' && typeof(controller != "undefined")) ? controller : window.location.href )
	addSpinner();
	
	$.post( controller + "/" + method, query, function(data) {
		killSpinner();
		$('#' + method ).html(data);
		$('#' + method ).modal();
	});
}

function clean_html(str)
{
    str = str.replace(/ on\w+="[^"]*"/g, '');
    //str = str.replace(/<script[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '');
    str = str.replace(/<script[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gim, '');
    return str;
}

function table_multy_submit(table)
{
	var obSelect	 = table.getElementsByTagName('select'); 
	var obInputs 	 = table.getElementsByTagName('input'); 
	var arIds		 = new Array();
	var action		 = false;
	
	/* choosing what to do */
	for (var i = 0; i < obSelect.length; i++) 
	{
		if (obSelect[i].value != '' && isNaN(obSelect[i].value) )
		{
			action = obSelect[i].value;
		}
		obSelect[i].selectedIndex = 0;
	}
	
	if ( ! action )	return false;
	
	for (var i = 0; i < obInputs.length; i++) 
	{
		if (obInputs[i].type == "checkbox" && obInputs[i].checked == true)
		{
			arIds.push(obInputs[i].id);
		}
	}
	
	if (arIds.length < 1) return false;
	
	var query = { 'ids[]' : arIds, query : action };	
	addSpinner();
	$.post( window.location.href + "/table_multy_submit", query, function(data) {
		killSpinner();
		if (data.charAt(0) == "{")
		{
			obData = $.parseJSON( data );
			if (obData.method == 'mailto')
			{
				//window.location = "mailto:" + obData.maillist + "?subject=" + obData.mailtitle;
                $('div.modal-body').html('<h4>Адреса рассылки</h4><p><strong>Русский интерфейс</strong><br/>' + obData.maillist_ru + '</p>' +
                    '<p><strong>Английский интерфейс</strong><br/>' + obData.maillist_en + '</p>');

                $('#ModalDefault').modal('show');



			} else if (obData.method == 'reload')
			{
				addSpinner();
				window.location.reload(true);
			}
		} else {
			$('#ModalDefault').html(data);
			$('#ModalDefault').modal();
		}
	});
	return;
}


function addSpinner()
{
	$('body').prepend('<div class="modal-backdrop fade in" style="z-index: 1040;"></div><div class="modal-scrollable" style="z-index: 1050;"><div class="loading-spinner fade in" style="width: 200px; margin-left: -100px; z-index: 1050;"><div class="progress progress-striped active"><div class="bar" style="width: 100%;"></div></div></div></div>');
	return;
}

function killSpinner()
{
	var modal_scrollable; 
	var backdrop = document.body.firstChild; 
	var classlist = backdrop.classList; 
	for (var i = 0; i < classlist.length; i++) {
		if (classlist[i] == "modal-backdrop" ) { 
			modal_scrollable = backdrop.nextSibling; 
		}
	} 
	
	$(modal_scrollable).remove(); 
	$(backdrop).remove();
	
	return;
}


$(function() {
		if (window.location.hash.length > 1) {
            //var id  = window.location.hash.replace(/^#/, '');
			$(window.location.hash).show();
        }
	})


$(function() {

    var PATH = 'protest';

	/**
	 * Drag and drop tags
	 *
	 */
	// there's the tagContainer and the projectTags
    var $tagContainer = $( "#tagContainer" ),
        $projectTags = $( "#projectTags" );
 	 
    // resolve the icons behavior with event delegation
    $( "ul.tagContainer > li" ).click(function( event ) {
      var $item = $( this ),
        $target = $( event.target );
 
      if ( $target.is( "a.ui-icon-refresh" ) ) {
        removeTag( $item );
      } else if ( $target.is( "a.ui-icon-delete" ) ){
		deleteTag ($item);
	  } else if ( $target.is( "a.add-tag" )) {
		addTag ($item);
	  }
 
      return false;
    });
 
    // let the tagContainer items be draggable
    $( "li", $tagContainer ).draggable({
      cancel: "a.ui-icon", // clicking an icon won't initiate dragging
      revert: "invalid", // when not dropped, the item will revert back to its initial position
      containment: "document",
      helper: "clone",
      cursor: "move"
    });
    
    // let the tagContainer items be draggable
    $( "li", $projectTags ).draggable({
      cancel: "a.ui-icon", // clicking an icon won't initiate dragging
      revert: "invalid", // when not dropped, the item will revert back to its initial position
      containment: "document",
      helper: "clone",
      cursor: "move"
    });
 
    // let the projectTags be droppable, accepting the tagContainer items
    $projectTags.droppable({
      accept: "#tagContainer > li",
      activeClass: "ui-state-highlight",
      drop: function( event, ui ) {
        addTag( ui.draggable );
      }
    });
 
    // let the tagContainer be droppable as well, accepting items from the projectTags
    $tagContainer.droppable({
      accept: "#projectTags li",
      activeClass: "custom-state-active",
      drop: function( event, ui ) {
        removeTag( ui.draggable );
      }
    });
 
    var recycle_icon = "  <a href='#' title='Remove tag' class='ui-icon-refresh ui-icon'>&times;</a>";
    function addTag( $item ) {
      $item.fadeOut(function() {
		var $list = $( "ul", $projectTags ).length ?
          $( "ul", $projectTags ) :
          $( "<ul class='tagContainer ui-helper-reset'/>" ).appendTo( $projectTags );
 
		$item.find( "a.ui-icon-delete" ).remove();
        $item.find( "a.ui-icon-refresh" ).remove();
        $item.append( recycle_icon ).appendTo( $list ).fadeIn();
      });
    }
 
 
	var action_icons = "  <a href='#' title='Delete tag' class='ui-icon-delete ui-icon'>&times;</a>";
    function removeTag( $item ) {
      $item.fadeOut(function() {
        $item
          .find( "a.ui-icon-refresh" )
            .remove()
          .end()
		  //.append( action_icons )
          .appendTo( $tagContainer )
          .fadeIn();
      });
    }
	
	function deleteTag(tag)
	{
		var tagID = $(tag).children('input[name="entry_tags[]"]').val();
		var action = $(tag).parents('ul').attr('rel');
		
		/* Hack for exclude already added tags */
		var exclude_collection = $('#projectTags input');
		var obExclude = [];
		exclude_collection.each(function (a, el){
			obExclude.push(el.value);
		});
		
		$.post( action, {"tagID" : tagID, "exclude" : obExclude}, function(data) {
			obData = $.parseJSON( data );
			if (obData.reloadtags == 1)
			{
				$tagContainer.html(obData.data);
				
				$( "li", $tagContainer ).draggable({
					cancel: "a.ui-icon", // clicking an icon won't initiate dragging
					revert: "invalid", // when not dropped, the item will revert back to its initial position
					containment: "document",
					helper: "clone",
					cursor: "move"
				});
				
				$( "ul.tagContainer > li" ).unbind('click');
				
				$( "ul.tagContainer > li" ).click(function( event ) {
					var $item = $( this ),
						$target = $( event.target );
					if ( $target.is( "a.ui-icon-refresh" ) ) {
						removeTag( $item );
					} else if ( $target.is( "a.ui-icon-delete" ) ){
						deleteTag ($item);
					} else if ( $target.is( "a.add-tag" )) {
						addTag ($item);
					}
					return false;
				});
			}
		});
	}

	$( "#addnewtag" ).submit(function(event){
		event.returnValue = false;
		if(event.preventDefault) event.preventDefault();
		
		var action  = $(this).attr('action');
		var tag 	= $(this).find('input[name=tagName]')
		var tagname = tag.val();
		
		/* Hack for exclude already added tags */
		var exclude_collection = $('#projectTags input');
		var obExclude = [];
		exclude_collection.each(function (a, el){
			obExclude.push(el.value);
		});
		
		$.post( action, {"tagname" : tagname, "exclude" : obExclude}, function(data) {
			tag.val('');
			obData = $.parseJSON( data );
			if (obData.reloadtags == 1)
			{
				$tagContainer.html(obData.data);
				
				$( "li", $tagContainer ).draggable({
					cancel: "a.ui-icon", // clicking an icon won't initiate dragging
					revert: "invalid", // when not dropped, the item will revert back to its initial position
					containment: "document",
					helper: "clone",
					cursor: "move"
				});

				$( "ul.tagContainer > li" ).unbind('click');
				
				$( "ul.tagContainer > li" ).click(function( event ) {
					var $item = $( this ),
						$target = $( event.target );
					if ( $target.is( "a.ui-icon-refresh" ) ) {
						removeTag( $item );
					} else if ( $target.is( "a.ui-icon-delete" ) ){
						deleteTag ($item);
					} else if ( $target.is( "a.add-tag" )) {
						addTag ($item);
					}
					return false;
				});
				
			} else if (typeof (obData.error) != "undefined" || obData.error != "")
			{
				console.log(obData.error);
				
				$('#ModalDefault .modal-body').html(obData.data);
				$('#ModalDefault').modal();
			}
		});
		
	});	
	
	$('#change_tag_name').submit(function(event){
		
		event.returnValue = false;
		if(event.preventDefault) event.preventDefault();
		
		var $this = $(this);
		var action 	   = $this.attr('action');
		var oldTagName = $this.find('input[name="oldTagName"]').val();
		var newTagName = $this.find('input[name="newTagName"]').val();
		
		$.post( action, {"oldTagName" : oldTagName, "newTagName" : newTagName}, function(data) {
			obData = $.parseJSON( data );
			if (obData.reloadtags == 1)
			{
				$tagContainer.html(obData.data);
				
				$( "li", $tagContainer ).draggable({
					cancel: "a.ui-icon", // clicking an icon won't initiate dragging
					revert: "invalid", // when not dropped, the item will revert back to its initial position
					containment: "document",
					helper: "clone",
					cursor: "move"
				});
				
				$( "ul.tagContainer > li" ).unbind('click');
				
				$( "ul.tagContainer > li" ).click(function( event ) {
					var $item = $( this ),
						$target = $( event.target );
 
					if ( $target.is( "a.ui-icon-refresh" ) ) {
						removeTag( $item );
					} else if ( $target.is( "a.ui-icon-delete" ) ){
						deleteTag ($item);
					} else if ( $target.is( "a.add-tag" )) {
						addTag ($item);
					}
					return false;
				});
				
				$this.parent('div.modal').modal('hide');
				
			} else if (typeof (obData.error) != "undefined" || obData.error != "")
			{
				$this.parent('div.modal').modal('hide');
				
				$('#ModalDefault .modal-body').html(obData.data);
				$('#ModalDefault').modal();
			}
		});
	});

    $('#subscribe').live('click', function(ev){
        ev.preventDefault();

        $.ajax({
            url: $(this).closest('form').attr('action'),
            type: 'POST',
            async: false,
            dataType: "text",
            data: {
                email: $('#subscr_email').val(),
                lang: $(':hidden[name=lang]').val()
            },
            success: function(msg) {
                $('#ModalDefault .modal-body').html(msg);
                $('#ModalDefault').modal();
                $('#subscr_email').val('');
            },
            error: function(response) {
                alert('Error: ' + response.responseText);
            }
        });
    });

    $('.treeNode').live('click', function(ev){
        ev.preventDefault();

        var $prev_right = $(this).data('left') - 1;


        console.log('cur_id='+$prev_right);

        $('#menu_ru_name').val($(this).text());
        $('#menu_en_name').val($(this).data('en_name'));
        $('#menu_url').val($(this).attr('href'));
        $('#menu_parent').val($(this).data('pid'));
        $('#menu_id').val($(this).data('id'));
        if($(this).data('active') == 1) {
            $('#menu_active').attr('checked','checked');
        } else {
            $('#menu_active').attr('checked', null);
        }



        $('#menu_prev option').each(function(){
            if( $(this).data('right') == $prev_right || $(this).data('left') == $prev_right) {
                $(this).attr('selected','selected');
                console.log('option val=' + $(this).val() + ' - ' + $prev_right);
            }
        });

    });

    $('#create_item').live('click', emptyForm);

    $('#menu_parent').live('change', function(){
        $('#menu_prev').val($(this).val());
    });

    function checkChildren($pid, $id)
    {
        var $reg = /\/(\w)+$/;
        var $post_url = location.href.replace($reg, '') + '/check_menu_items';
        var $res = -2;

        $.ajax({
            url: $post_url,
            type: 'POST',
            async: false,
            dataType: "text",
            data: { pid: $pid, id: $id },
            success: function(data){ console.log('check children data: '+data);
                switch (data) {
                    case '1':
                        $res = 1;
                        break;
                    case '0':
                        $res = 0;
                        break;
                    default:
                        $res = -1;
                }
            },
            error: function(response) {
                alert('Error: ' + response.responseText);
            }
        });

        return $res;
    }

    $('#save_item').live('click', function(ev){
        ev.preventDefault();
        var $reg = /\/(\w)+$/;
        var $post_url = location.href.replace($reg, '') + '/save_menu_item';
        var $active = 0;
        var $parent_item = $('#menu_parent');
        var $menu_prev = $('#menu_prev');
        var $prev_id = $menu_prev.val();

        var $check_items = checkChildren($parent_item.val(), $prev_id);

        if($check_items == 1) {
           // console.log('pid='+$parent_item.val()+' prev id='+$prev_id);
            if($('#menu_active').is(':checked')) $active = 1;

            // определим id предыдущего пункта меню, если "КОРЕНЬ", то выбираем последний пункт из доступных
            //if( $prev_id == 0 ) { $prev_id = $menu_prev.find('option').last().val(); }

            console.log('post_url: ' + $post_url + ' url: ' + $('#menu_url').val() + ' id: ' + $('#menu_id').val());

            $.post($post_url, {id: $('#menu_id').val(),
                ru_name: $('#menu_ru_name').val(),
                en_name: $('#menu_en_name').val(),
                url: $('#menu_url').val(),
                parent: $parent_item.val(),
                active: $active,
                type: $('#menu_type').val(),
                prev_id: $prev_id },
                function(data){
                    if(data == '1') { console.log(data);
                        // перечитаем и покажем дерево меню и список ветвей (<select>)
                        $post_url = location.href.replace($reg, '') + '/tree_reload';
                        $.post($post_url, {type: $('#menu_type').val()}, function(data){
                            $('#menu_tree').html(data.tree);
                            $parent_item.html(data.select_options);
                            $menu_prev.html(data.select_options);
                            }, 'json');
                    } else {
                        console.log(data);
                    }

                    emptyForm();
                });
        } else if($check_items == 0) {
            alert('Предыдущий пункт не является прямым потомком родительского пункта!');
        } else { alert('Ошибка: ' + $check);
            console.log($check_items);
        }

    });

    $('#delete_item').live('click', function(ev){
        ev.preventDefault();

        var $reg = /\/(\w)+$/;
        var $post_url = location.href.replace($reg, '') + '/delete_menu_item';
        $.post($post_url, {id: $('#menu_id').val()}, function(data){
            if(data == '1') {
                // перечитаем и покажем дерево меню и список ветвей (<select>)
                $post_url = location.href.replace($reg, '') + '/tree_reload';
                $.post($post_url, {type: $('#menu_type').val()}, function(data){
                    $('#menu_tree').html(data.tree);
                    $('#menu_parent').html(data.select_options);
                    $('#menu_prev').html(data.select_options);
                    }, 'json');
            } else {
                alert(data);

            }
            // очистим поля формы
            emptyForm();
        });
    });


    function emptyForm()
    {
        $('#menu_ru_name').val('');
        $('#menu_en_name').val('');
        $('#menu_url').val('');
        $('#menu_parent').val(0);
        $('#menu_id').val(0); // input hidden
        $('#menu_active').attr('checked', null);
        $('#menu_prev').val(0);
    }

    $('#menu_type').live('change', function(){
        var $reg = /\/(\w)+$/;
        // очистим поля формы
        emptyForm();

        // перечитаем и покажем дерево меню и список ветвей (<select>)
        $post_url = location.href.replace($reg, '') + '/tree_reload';
        $.post($post_url, {type: $(this).val()}, function(data){
            $('#menu_tree').html(data.tree);
            $('#menu_parent').html(data.select_options);
            $('#menu_prev').html(data.select_options);
            }, 'json');
    });

    // удаление картинки из анонса блога
    $('#del_announce_img').live('click', function(ev){
        ev.preventDefault();

        console.log(' del click ' + $('#current_announce_img').attr('src').replace('http://' + location.hostname + '/protest/', ''));

        del_announce_image('blog', $('#current_announce_img').attr('src').replace('http://' + location.hostname + '/protest/', ''), $(this).data('id'));
/*
        $.ajax({
            type: "POST",
            async: false,
            url: 'blog/delete_announce_img',
            data: {'img_path' : $('#current_announce_img').attr('src').replace('http://' + location.hostname + '/protest/', ''), 'id' : $(this).data('id')},
            dataType: 'text',
            success: function(data){
                console.log('data=' + data);
                if(data == 1) {
                    $('#current_announce_img').closest('div').hide()
                    $('#del_announce_img').closest('div').hide();
                }
            },
            error: function(response) {
                alert('Error: ' + response.responseText);
            }
        });*/

    });

    // удаление картинки из анонса статьи
    $('#del_announce_img').live('click', function(ev){
        ev.preventDefault();

        console.log(' del click ' + $('#current_announce_img').attr('src').replace('http://' + location.hostname + '/protest/', ''));

        del_announce_image('administration/articles', $('#current_announce_img').attr('src').replace('http://' + location.hostname + '/protest/', ''), $(this).data('id'));
        /*
         $.ajax({
         type: "POST",
         async: false,
         url: 'blog/delete_announce_img',
         data: {'img_path' : $('#current_announce_img').attr('src').replace('http://' + location.hostname + '/protest/', ''), 'id' : $(this).data('id')},
         dataType: 'text',
         success: function(data){
         console.log('data=' + data);
         if(data == 1) {
         $('#current_announce_img').closest('div').hide()
         $('#del_announce_img').closest('div').hide();
         }
         },
         error: function(response) {
         alert('Error: ' + response.responseText);
         }
         });*/

    });

    function del_announce_image($source, $img_path, $id)
    {
        $.ajax({
            type: "POST",
            async: false,
            url: $source + '/delete_announce_img',
            data: {'img_path' : $img_path, 'id' : $id},
            dataType: 'text',
            success: function(data){
                console.log('data=' + data);
                if(data == 1) {
                    $('#current_announce_img').closest('div').hide()
                    $('#del_announce_img').closest('div').hide();
                }
            },
            error: function(response) {
                console.log(response.responseText);
                alert('Error: ' + response.responseText);
            }
        });
    }

    // редактирование тэгов в амдинке
    $('i[id^=edit]').live('click', function(){
        var $tag_id = $(this).attr('id').replace('edit','');
        var $td_name = $(this).closest('tr').children('td').eq(1);
        //var $td_lang = $(this).closest('tr').children('td').eq(2);
        var $name_placeholder = $td_name.text();
        //var $lang_placeholder = $td_lang.text();


        removeEl($('#cur_name_edit')); // удаляем все открытые input
       // removeEl($('#cur_lang_edit')); // удаляем все открытые select

        $('i[id^=commit]').hide(); // показываем скрытую иконку редактирования
        $('i[id^=rollback]').hide();
        $('i[id^=edit]').show();

        // добавляем поле редактирования в name
        $td_name.text('').append('<input type="text" data-oldvalue="' + $name_placeholder + '" value="' + $name_placeholder + '" id="cur_name_edit" style="margin-bottom: 0" />');

      /*  $td_lang.text('').append('<select id="cur_lang_edit" style="width:100px" data-oldvalue="' + $lang_placeholder + '"><option value="en" ' + (( $lang_placeholder == 'en' ) ? 'selected' : '') + '>en</option>' +
                                 '<option value="ru" ' + (( $lang_placeholder == 'ru' ) ? 'selected' : '') + '>ru</option></select>');
*/
        $(this).hide(); // скрываем иконку редактирования
        $('i[id=commit' + $tag_id + ']').show();
        $('i[id=rollback' + $tag_id + ']').show();
    });

    // отмена изменений в редактировании тэгов
    $('i[id^=rollback]').live('click', function(){
        var $tag_id = $(this).attr('id').replace('rollback','');

        // скрываем поля редактирования и возвращаем старые значения в таблицу
        removeEl($('#cur_name_edit'));
       // removeEl($('#cur_lang_edit'));

        // скрываем не нужные иконки (commit, rollback)
        $('#commit' + $tag_id).hide();
        $(this).hide();
        $('#edit' + $tag_id).show(); // показываем иконку редактирования
    });

    // сохранение изменений в редактировании тэгов
    $('i[id^=commit]').live('click', function(){
        var $tag_id = $(this).attr('id').replace('commit','');
        var $td_name = $(this).closest('tr').children('td').eq(1);
        var $cur_name_edit = $('#cur_name_edit');
        var $this = $(this);

        //$('#cur_name_edit').val()
        $.post(location.href + '/save', {id: $tag_id, name: $cur_name_edit.val()}, function(msg){
            alert(msg);
            if(msg == 1) {
                alert('Тэг успешно обновлен');

                $td_name.text($cur_name_edit.val());
                $cur_name_edit.remove();
            }
            else {
                alert('Тэг не удалось обновить');

                $td_name.text($cur_name_edit.data('oldvalue'));
                $cur_name_edit.remove();
            }

            // скрываем не нужные иконки
            $this.hide();
            $('i[id^=rollback' + $tag_id + ']').hide();
            $('i[id^=edit' + $tag_id + ']').show();
        });
        //console.log(location.href);



    });

    // удаление тэга
    $('i[id^=del]').live('click', function(){
        var $tag_id = $(this).attr('id').replace('del','');
        var $cur_name_edit = $('#cur_name_edit');
        var $this = $(this);

        if (confirm('Вы действительно хотите удалить этот тэг?')) {
            // do things if OK
            $.post(location.href + '/delete', {id: $tag_id, name: $cur_name_edit.val()}, function(msg){
                alert(msg);
                switch (msg)
                {
                    case '0' :
                        alert('Тэг не удалось удалить!');
                        break;
                    case '10':
                        alert('Тэг успешно удален.\n Не удалено ни одной связи!' );
                        $this.closest('tr').remove();
                        $('div.tooltip').remove();
                        break;
                    case '11':
                        alert('Тэг успешно удален.\nВсе связи успешно удалены.');
                        $this.closest('tr').remove();
                        $('div.tooltip').remove();
                        break;
                }
            });
        }

    });

    function removeEl(el)
    {
        el.parent('td').text(el.data('oldvalue'));
        el.remove();
    }

    // редактирование страниц в амдинке
    $('i[id^=p_edit]').live('click', function(){
        var $page_id = $(this).attr('id').replace('p_edit','');

        location.href = location.href + '/edit/' + $page_id;
    });

    // удаление страницы
    $('i[id^=p_del]').live('click', function(){
        var $page_id = $(this).attr('id').replace('p_del','');
        var $this = $(this);

        if (confirm('Вы действительно хотите удалить эту страницу?')) {
            // do things if OK
            $.post(location.href + '/delete', {id: $page_id}, function(msg){
                if(msg) {
                    alert('Страница успешно удалена.');
                    $this.closest('tr').remove();
                }
                else
                    alert('Не удалось удалить страницу!');

            });
        }

    });


    $('#cb_sameaddress').live('change', function(){

        if($(this).is(':checked')) {
            $('#input_actualcountry').val($('#input_legalcountry').val());
            $('#input_actualprovince').val($('#input_legalprovince').val());
            $('#input_actualcity').val($('#input_legalcity').val());
            $('#input_actualpostalcode').val($('#input_legalpostalcode').val());
            $('#input_actualaddress').val($('#input_legaladdress').val());
        } else {
            $('#input_actualcountry').val('');
            $('#input_actualprovince').val('');
            $('#input_actualcity').val('');
            $('#input_actualpostalcode').val('');
            $('#input_actualaddress').val('');
        }
    });

    $('#distrib_contact').on('click',function(ev){
        ev.preventDefault();

        /* addSpinner();
        $.post( , function(data) {
            killSpinner();
            if (data.charAt(0) == "{")
            {
                obData = $.parseJSON( data );
                if (obData.method == 'mailto')
                {
                    //window.location = "mailto:" + obData.maillist + "?subject=" + obData.mailtitle;
                    $('div.modal-body').html('<h4>Адреса рассылки</h4><p><strong>Русский интерфейс</strong><br/>' + obData.maillist_ru + '</p>' +
                        '<p><strong>Английский интерфейс</strong><br/>' + obData.maillist_en + '</p>');

                    $('#ModalDefault').modal('show');



                } else if (obData.method == 'reload')
                {
                    addSpinner();
                    window.location.reload(true);
                }
            } else {*/
                //$('#ModalDefault').html('asdfasdf');
                $('div.modal-body').html('<h4>Контакты дистрибьютора</h4>' +
                    '<p><strong>Компания: </strong></p>' +
                    '<p><strong>Адрес: </strong></p>' +
                    '<p><strong>Контактное лицо: </strong></p>' +
                    '<p><strong>Телефон: </strong></p>' +
                    '<p><strong>E-mail: </strong></p>'
                );
                $('#ModalDefault').modal('show');
          /*  }
        });*/
    });

});

function test(me)
{
	alert('hahah, its me! its me, great ' + me + '!!');
}