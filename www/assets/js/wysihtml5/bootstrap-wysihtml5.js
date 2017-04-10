!function($, wysi) {
    "use strict";
    var tpl = {
        "font-styles": function(locale) {
            return "<li class='dropdown'>" +
              "<a class='btn dropdown-toggle' data-toggle='dropdown' href='#'>" +
              "<i class='icon-font'></i>&nbsp;<span class='current-font'>" + locale.font_styles.normal + "</span>&nbsp;<b class='caret'></b>" +
              "</a>" +
              "<ul class='dropdown-menu'>" +
                "<li><a data-wysihtml5-command='formatBlock' data-wysihtml5-command-value='div'>" + locale.font_styles.normal + "</a></li>" +
                "<li><a data-wysihtml5-command='formatBlock' data-wysihtml5-command-value='h1'>" + locale.font_styles.h1 + "</a></li>" +
                "<li><a data-wysihtml5-command='formatBlock' data-wysihtml5-command-value='h2'>" + locale.font_styles.h2 + "</a></li>" +
                "<li><a data-wysihtml5-command='formatBlock' data-wysihtml5-command-value='h3'>" + locale.font_styles.h3 + "</a></li>" +
              "</ul>" +
            "</li>";
        },
        "emphasis": function(locale) {
            return "<li>" +
              "<div class='btn-group'>" +
                "<a class='btn' data-wysihtml5-command='bold' title='CTRL+B'>" + locale.emphasis.bold + "</a>" +
                "<a class='btn' data-wysihtml5-command='italic' title='CTRL+I'>" + locale.emphasis.italic + "</a>" +
                "<a class='btn' data-wysihtml5-command='underline' title='CTRL+U'>" + locale.emphasis.underline + "</a>" +
              "</div>" +
            "</li>";
        },
        "lists": function(locale) {
            return "<li>" +
              "<div class='btn-group'>" +
                "<a class='btn' data-wysihtml5-command='insertUnorderedList' title='" + locale.lists.unordered + "'><i class='icon-list'></i></a>" +
                "<a class='btn' data-wysihtml5-command='insertOrderedList' title='" + locale.lists.ordered + "'><i class='icon-th-list'></i></a>" +
                "<a class='btn' data-wysihtml5-command='Outdent' title='" + locale.lists.outdent + "'><i class='icon-indent-right'></i></a>" +
                "<a class='btn' data-wysihtml5-command='Indent' title='" + locale.lists.indent + "'><i class='icon-indent-left'></i></a>" +
              "</div>" +
            "</li>";
        },
        "link": function(locale) {
            return "<li>" +
              "<div class='bootstrap-wysihtml5-insert-link-modal modal hide fade'>" +
                "<div class='modal-header'>" +
                  "<a class='close' data-dismiss='modal'>&times;</a>" +
                  "<h3>" + locale.link.insert + "</h3>" +
                "</div>" +
                "<div class='modal-body'>" +
                  "<input value='http://' class='bootstrap-wysihtml5-insert-link-url input-xlarge'>" +
                "</div>" +
                "<div class='modal-footer'>" +
                  "<a href='#' class='btn' data-dismiss='modal'>" + locale.link.cancel + "</a>" +
                  "<a href='#' class='btn btn-primary' data-dismiss='modal'>" + locale.link.insert + "</a>" +
                "</div>" +
              "</div>" +
              "<a class='btn' data-wysihtml5-command='createLink' title='" + locale.link.insert + "'><i class='icon-share'></i></a>" +
            "</li>";
        },
        "abbr": function(locale) {
            return "<li>" +
              "<div class='bootstrap-wysihtml5-insert-abbr-modal modal hide fade'>" +
                "<div class='modal-header'>" +
                  "<a class='close' data-dismiss='modal'>&times;</a>" +
                  "<h3>" + locale.abbr.insert + "</h3>" +
                "</div>" +
                "<div class='modal-body'>" +
					"<div class='control-group'>" +
							"<label for='hidden'>" + locale.abbr.hidden + "</label>" +
							"<div class='controls'>" +
								"<input name='hidden' class='bootstrap-wysihtml5-insert-abbr-title input-xxlarge' id='hidden'>" +
							"</div>" +
	                    "</div>" +
                "</div>" +
                "<div class='modal-footer'>" +
                  "<a href='#' class='btn' data-dismiss='modal'>" + locale.abbr.cancel + "</a>" +
                  "<a href='#' class='btn btn-primary' data-dismiss='modal'>" + locale.abbr.insert + "</a>" +
                "</div>" +
              "</div>" +
              "<a class='btn' data-wysihtml5-command='createAbbr' title='" + locale.abbr.insert + "'>ABBR</a>" +
            "</li>";
        },
        "image": function(locale) {
            return "<li>" +
              "<div class='bootstrap-wysihtml5-insert-image-modal modal hide fade'>" +
                "<div class='modal-header'>" +
                  "<a class='close' data-dismiss='modal'>&times;</a>" +
                  "<h3>" + locale.image.insert + "</h3>" +
                "</div>" +
                "<div class='modal-body'>" +
					"<div class='control-group'>" +
						"<input type='file' name='entry_image' style='color:#fff'>" +
					"</div><div class='control-group'>" +
						"<input type='text' value='http://' class='bootstrap-wysihtml5-insert-image-url input-xlarge'>" +
					"</div>" +
					"<div class='control-group error form-inline'>" +
						"<label class='image-error hide'></label>" +
					"</div>" +
                "</div>" +
                "<div class='modal-footer'>" +
                  "<a href='#' class='btn' data-dismiss='modal'>" + locale.image.cancel + "</a>" +
                  "<a href='#' class='btn btn-primary' data-dismiss='modal'>" + locale.image.insert + "</a>" +
                "</div>" +
              "</div>" +
              "<a class='btn' data-wysihtml5-command='insertImage' title='" + locale.image.insert + "'><i class='icon-picture'></i></a>" +
            "</li>";
        },
        "video": function(locale) { 
    		return "<li>" +
	            "<div class='bootstrap-wysihtml5-insert-video-modal modal hide fade'>" +
	                "<div class='modal-header'>" +
	                    "<a class='close' data-dismiss='modal'>&times;</a>" +
	                    "<h3>" + locale.video.insert + "</h3>" +
	                "</div>" +
	                "<div class='modal-body'>" +
	                    "<div class='control-group'>" +
	                        "<input type='text' data-wysihtml5-dialog-field='src' value='http://' class='bootstrap-wysihtml5-insert-video-url input-xlarge'>" +
	                    "</div>" +
	                    "<div class='control-group error form-inline'>" +
                    		"<label class='video-error hide'>" + locale.video.invalid + "</label>" +
	                    "</div>" +
	                "</div>" +
	                "<div class='modal-footer'>" +
	                    "<a href='#' class='btn' data-dismiss='modal' data-wysihtml5-dialog-action='cancel'>" + locale.video.cancel + "</a>" +
	                    "<a href='#' class='btn btn-primary' data-dismiss='modal' data-wysihtml5-dialog-action='save'>" + locale.video.insert + "</a>" +
	                "</div>" +
	            "</div>" +
	            "<a class='btn' data-wysihtml5-command='insertVideo' title='" + locale.video.insert + "'><i class='icon-film'></i></a>" +
	       "</li>";
	    },
		"tooltip": function(locale) { 
    		return "<li>" +
	            "<div class='bootstrap-wysihtml5-insert-tooltip-modal modal hide fade'>" +
	                "<div class='modal-header'>" +
	                    "<a class='close' data-dismiss='modal'>&times;</a>" +
	                    "<h3>" + locale.tooltip.insert + "</h3>" +
	                "</div>" +
	                "<div class='modal-body'>" +
	                    "<div class='control-group'>" +
							"<label for='shorty'>" + locale.tooltip.shorty + "</label>" +
							"<div class='controls'>" +
								"<input type='text' data-wysihtml5-dialog-field='title' name='shorty' class='bootstrap-wysihtml5-insert-tooltip-url input-xxlarge' id='shorty'>" +
							"</div>" +
	                    "</div>" +
	                    "<div class='control-group'>" +
                    		"<label for='hidden'>" + locale.tooltip.hidden + "</label>" +
							"<div class='controls'>" +
								"<textarea type='text' data-wysihtml5-dialog-field='content' name='hidden' class='bootstrap-wysihtml5-insert-tooltip-url input-xxlarge' id='hidden'></textarea>" +
							"</div>" +
	                    "</div>" +
	                "</div>" +
	                "<div class='modal-footer'>" +
	                    "<a href='#' class='btn' data-dismiss='modal' data-wysihtml5-dialog-action='cancel'>" + locale.tooltip.cancel + "</a>" +
	                    "<a href='#' class='btn btn-primary' data-dismiss='modal' data-wysihtml5-dialog-action='save'>" + locale.tooltip.insert + "</a>" +
	                "</div>" +
	            "</div>" +
	            "<a class='btn' data-wysihtml5-command='createTooltip' title='" + locale.tooltip.insert + "'><i class='icon-comment'></i></a>" +
	       "</li>";
	    },
        "html": function(locale) {
            return "<li>" +
              "<div class='btn-group'>" +
                "<a class='btn' data-wysihtml5-action='change_view' title='" + locale.html.edit + "'><i class='icon-pencil'></i></a>" +
              "</div>" +
            "</li>";
        },
        "color": function(locale) {
            return "<li class='dropdown'>" +
              "<a class='btn dropdown-toggle' data-toggle='dropdown' href='#'>" +
                "<span class='current-color'>" + locale.colours.black + "</span>&nbsp;<b class='caret'></b>" +
              "</a>" +
              "<ul class='dropdown-menu'>" +
                "<li><div class='wysihtml5-colors' data-wysihtml5-command-value='black'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='black'>" + locale.colours.black + "</a></li>" +
                "<li><div class='wysihtml5-colors' data-wysihtml5-command-value='silver'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='silver'>" + locale.colours.silver + "</a></li>" +
                "<li><div class='wysihtml5-colors' data-wysihtml5-command-value='gray'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='gray'>" + locale.colours.gray + "</a></li>" +
                "<li><div class='wysihtml5-colors' data-wysihtml5-command-value='maroon'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='maroon'>" + locale.colours.maroon + "</a></li>" +
                "<li><div class='wysihtml5-colors' data-wysihtml5-command-value='red'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='red'>" + locale.colours.red + "</a></li>" +
                "<li><div class='wysihtml5-colors' data-wysihtml5-command-value='purple'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='purple'>" + locale.colours.purple + "</a></li>" +
                "<li><div class='wysihtml5-colors' data-wysihtml5-command-value='green'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='green'>" + locale.colours.green + "</a></li>" +
                "<li><div class='wysihtml5-colors' data-wysihtml5-command-value='olive'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='olive'>" + locale.colours.olive + "</a></li>" +
                "<li><div class='wysihtml5-colors' data-wysihtml5-command-value='navy'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='navy'>" + locale.colours.navy + "</a></li>" +
                "<li><div class='wysihtml5-colors' data-wysihtml5-command-value='blue'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='blue'>" + locale.colours.blue + "</a></li>" +
                "<li><div class='wysihtml5-colors' data-wysihtml5-command-value='orange'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='orange'>" + locale.colours.orange + "</a></li>" +
              "</ul>" +
            "</li>";
        }
    };
    var templates = function(key, locale) {
        return tpl[key](locale);
    };
    var Wysihtml5 = function(el, options) {
        this.el = el;
        var toolbarOpts = options || defaultOptions;
        for(var t in toolbarOpts.customTemplates) {
          tpl[t] = toolbarOpts.customTemplates[t];
        }
        this.toolbar = this.createToolbar(el, toolbarOpts);
        this.editor =  this.createEditor(options);

        window.editor = this.editor;

        $('iframe.wysihtml5-sandbox').each(function(i, el){
            $(el.contentWindow).off('focus.wysihtml5').on({
                'focus.wysihtml5' : function(){
                    $('li.dropdown').removeClass('open');
                }
            });
        });
    };
    Wysihtml5.prototype = {
        constructor: Wysihtml5,
        createEditor: function(options) {
            options = options || {};
            options.toolbar = this.toolbar[0];

            var editor = new wysi.Editor(this.el[0], options);

            if(options && options.events) {
                for(var eventName in options.events) {
                    editor.on(eventName, options.events[eventName]);
                }
            }
            return editor;
        },
        createToolbar: function(el, options) {
            var self = this;
            var toolbar = $("<ul/>", {
                'class' : "wysihtml5-toolbar",
                'style': "display:none"
            });
            var culture = options.locale || defaultOptions.locale || "en";
            for(var key in defaultOptions) {
                var value = false;
                if(options[key] !== undefined) {
                    if(options[key] === true) {
                        value = true;
                    }
                } else {
                    value = defaultOptions[key];
                }
                if(value === true) {
                    toolbar.append(templates(key, locale[culture]));

                    if(key === "html") {
                        this.initHtml(toolbar);
                    }

                    if(key === "link") {
                        this.initInsertLink(toolbar);
                    }

                    if(key === "image") {
                        this.initInsertImage(toolbar,options);
                    }
                    
                    if(key === "video") {
                        this.initInsertVideo(toolbar);
                    }
					
                    if(key === "tooltip") {
                        this.initTooltip(toolbar);
                    }
					
                    if(key === "abbr") {
                        this.initInsertAbbr(toolbar);
                    }
                }
            }

            if(options.toolbar) {
                for(key in options.toolbar) {
                    toolbar.append(options.toolbar[key]);
                }
            }
            toolbar.find("a[data-wysihtml5-command='formatBlock']").click(function(e) {
                var target = e.target || e.srcElement;
                var el = $(target);
                self.toolbar.find('.current-font').text(el.html());
            });
            toolbar.find("a[data-wysihtml5-command='foreColor']").click(function(e) {
                var target = e.target || e.srcElement;
                var el = $(target);
                self.toolbar.find('.current-color').text(el.html());
            });
            this.el.before(toolbar);
            return toolbar;
        },

        initHtml: function(toolbar) {
            var changeViewSelector = "a[data-wysihtml5-action='change_view']";
            toolbar.find(changeViewSelector).click(function(e) {
                toolbar.find('a.btn').not(changeViewSelector).toggleClass('disabled');
            });
        },
		initInsertImage: function(toolbar, options) {
			var self = this;
			var insertImageModal = toolbar.find('.bootstrap-wysihtml5-insert-image-modal');
			var urlInput = insertImageModal.find('.bootstrap-wysihtml5-insert-image-url');
			var errorContainer = insertImageModal.find('.image-error');
			var insertButton = insertImageModal.find('a.btn-primary');
			var uploader = insertImageModal.find('input[type=file]');
			var initialValue = urlInput.val();
			var insertImage = function() {
                var url = urlInput.val();
                urlInput.val(initialValue);
                self.editor.currentView.element.focus();
                self.editor.composer.commands.exec("insertImage", url);
            };
			uploader.fileupload({
				url	: options.fileuploadcallback,
				dataType: 'json',
				done: function (e, data) {
				console.log(data.result);
					if (data.result.error.length > 0)
					{
						errorContainer.show();
						errorContainer.html(data.result.error);
					} else 
					{
						errorContainer.hide();
						urlInput.val(data.result.link);
					}
				}
			});
			urlInput.keypress(function(e) {
                if(e.which == 13) {
                    insertImage();
                    insertImageModal.modal('hide');
                }
            });
			insertButton.click(insertImage);

            insertImageModal.on('shown', function() {
                urlInput.focus();
            });
            insertImageModal.on('hide', function() {
                self.editor.currentView.element.focus();
            });
			toolbar.find('a[data-wysihtml5-command=insertImage]').click(function() {
                var activeButton = $(this).hasClass("wysihtml5-command-active");

                if (!activeButton) {
                    insertImageModal.modal('show');
                    insertImageModal.on('click.dismiss.modal', '[data-dismiss="modal"]', function(e) {
                        e.stopPropagation();
                    });
                    return false;
                }
                else {
                    return true;
                }
            });
		},
        initInsertVideo: function(toolbar) {
            var self = this;
            var insertVideoModal = toolbar.find('.bootstrap-wysihtml5-insert-video-modal');
            var urlInput = insertVideoModal.find('.bootstrap-wysihtml5-insert-video-url');
            var insertButton = insertVideoModal.find('a.btn-primary');
            var errorSpan = insertVideoModal.find('.video-error');
            var insertVideo = function() {
                errorSpan.hide();
                //urlInput.parent().removeClass('error');
                var linkUrl = urlInput.val();
                var embedUrl = linkUrl;
                if ( linkUrl.substr(0,31) == 'http://www.youtube.com/watch?v=' ) {
                    var linkParams = linkUrl.substr(31);
                    embedUrl = 'http://www.youtube.com/embed/' + linkParams.split('&')[0];
                    urlInput.val(linkUrl);
                    self.editor.currentView.element.focus();
                    self.editor.composer.commands.exec("insertVideo", { src: embedUrl, width: '640', height: '480' });
                } else {
                    errorSpan.show();
                    //urlInput.parent().addClass('error');
                    return false;
                }
            };
            urlInput.keypress(function(e) {
                if(e.which == 13) {
                    insertVideo();
                    insertVideoModal.modal('hide');
                }
            });
            insertButton.click(insertVideo);
            insertVideoModal.on('shown', function() {
                urlInput.focus();
            });
            insertVideoModal.on('hide', function() {
                self.editor.currentView.element.focus();
            });
            toolbar.find('a[data-wysihtml5-command=insertVideo]').click(function() {
                insertVideoModal.modal('show');
                insertVideoModal.on('click.dismiss.modal', '[data-dismiss="modal"]', function(e) {
                    e.stopPropagation();
                });
                return false;
            });
        },
        initInsertLink: function(toolbar) {
            var self = this;
            var insertLinkModal = toolbar.find('.bootstrap-wysihtml5-insert-link-modal');
            var urlInput = insertLinkModal.find('.bootstrap-wysihtml5-insert-link-url');
            var insertButton = insertLinkModal.find('a.btn-primary');
            var initialValue = urlInput.val();
            var insertLink = function() {
                var url = urlInput.val();
                urlInput.val(initialValue);
                self.editor.currentView.element.focus();
                self.editor.composer.commands.exec("createLink", {
                    href: url,
                    target: "_blank",
                    rel: "nofollow"
                });
            };
            var pressedEnter = false;
            urlInput.keypress(function(e) {
                if(e.which == 13) {
                    insertLink();
                    insertLinkModal.modal('hide');
                }
            });
            insertButton.click(insertLink);
            insertLinkModal.on('shown', function() {
                urlInput.focus();
            });
            insertLinkModal.on('hide', function() {
                self.editor.currentView.element.focus();
            });
            toolbar.find('a[data-wysihtml5-command=createLink]').click(function() {
                var activeButton = $(this).hasClass("wysihtml5-command-active");
                if (!activeButton) {
                    insertLinkModal.appendTo('body').modal('show');
                    insertLinkModal.on('click.dismiss.modal', '[data-dismiss="modal"]', function(e) {
                        e.stopPropagation();
                    });
                    return false;
                }
                else {
                    return true;
                }
            });
        },
		initInsertAbbr: function(toolbar) {
            var self = this;
            var insertAbbrModal = toolbar.find('.bootstrap-wysihtml5-insert-abbr-modal');
            var titleInput = insertAbbrModal.find('.bootstrap-wysihtml5-insert-abbr-title');
            var insertButton = insertAbbrModal.find('a.btn-primary');
            var initialValue = titleInput.val();
            var insertAbbr = function() {
                var title = titleInput.val();
                titleInput.val(initialValue);
                self.editor.currentView.element.focus();
                self.editor.composer.commands.exec("createAbbr", {
                    title: title
                });
            };
            var pressedEnter = false;

            titleInput.keypress(function(e) {
                if(e.which == 13) {
                    insertAbbr();
                    insertAbbrModal.modal('hide');
                }
            });
            insertButton.click(insertAbbr);
            insertAbbrModal.on('shown', function() {
                titleInput.focus();
            });
            insertAbbrModal.on('hide', function() {
                self.editor.currentView.element.focus();
            });
            toolbar.find('a[data-wysihtml5-command=createAbbr]').click(function() {
                var activeButton = $(this).hasClass("wysihtml5-command-active");
                if (!activeButton) {
                    insertAbbrModal.appendTo('body').modal('show');
                    insertAbbrModal.on('click.dismiss.modal', '[data-dismiss="modal"]', function(e) {
                        e.stopPropagation();
                    });
                    return false;
                }
                else {
                    return true;
                }
            });
        },
		initTooltip: function(toolbar, options) {
			var self = this;
			var insertTooltipModal = toolbar.find('.bootstrap-wysihtml5-insert-tooltip-modal');
			var hiddenContent = insertTooltipModal.find('textarea[name=hidden]');
			var shortyContent = insertTooltipModal.find('input[name=shorty]');
			var insertButton = insertTooltipModal.find('a.btn-primary');
			var initialValueHidden = hiddenContent.val();
			var initialValueShorty = shortyContent.val();
			var insertTooltip = function() {
                var valHidden = hiddenContent.val();
				var valShorty = shortyContent.val();
                hiddenContent.val(initialValueHidden);
				shortyContent.val(initialValueShorty);
                self.editor.currentView.element.focus();
                self.editor.composer.commands.exec("createLink", {
					href:				  "#",
					rel:	 			  "tooltip",
					"data-placement":	  "top",
                    "data-original-title":valHidden,
					text:				  valShorty
                });
            };
			insertButton.click(insertTooltip);
            insertTooltipModal.on('shown', function() {
                shortyContent.focus();
            });
            insertTooltipModal.on('hide', function() {
                self.editor.currentView.element.focus();
            });
			toolbar.find('a[data-wysihtml5-command=createTooltip]').click(function() {
                var activeButton = $(this).hasClass("wysihtml5-command-active");
                if (!activeButton) {
                    insertTooltipModal.modal('show');
                    insertTooltipModal.on('click.dismiss.modal', '[data-dismiss="modal"]', function(e) {
                        e.stopPropagation();
                    });
                    return false;
                }
                else {
                    return true;
                }
            });
		}
	}
    // these define our public api
	var methods = {
        resetDefaults: function() {
            $.fn.wysihtml5.defaultOptions = $.extend(true, {}, $.fn.wysihtml5.defaultOptionsCache);
        },
        bypassDefaults: function(options) {
            return this.each(function () {
                var $this = $(this);
                $this.data('wysihtml5', new Wysihtml5($this, options));
            });
        },
        shallowExtend: function (options) {
            var settings = $.extend({}, $.fn.wysihtml5.defaultOptions, options || {});
            var that = this;
            return methods.bypassDefaults.apply(that, [settings]);
        },
        deepExtend: function(options) {
            var settings = $.extend(true, {}, $.fn.wysihtml5.defaultOptions, options || {});
            var that = this;
            return methods.bypassDefaults.apply(that, [settings]);
        },
        init: function(options) {
            var that = this;
            return methods.shallowExtend.apply(that, [options]);
        }
    };
    $.fn.wysihtml5 = function ( method ) {
        if ( methods[method] ) {
            return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.wysihtml5' );
        }    
    };
    $.fn.wysihtml5.Constructor = Wysihtml5;
    var defaultOptions = $.fn.wysihtml5.defaultOptions = {
        "font-styles": true,
        "color": false,
        "emphasis": true,
        "lists": true,
        "html": false,
        "link": true,
        "image": true,
        "video": true,
		"tooltip": true,
		"abbr": true,
        events: {},
        parserRules: {
            classes: {
                // (path_to_project/lib/css/wysiwyg-color.css)
                "wysiwyg-color-silver" : 1,
                "wysiwyg-color-gray" : 1,
                "wysiwyg-color-white" : 1,
                "wysiwyg-color-maroon" : 1,
                "wysiwyg-color-red" : 1,
                "wysiwyg-color-purple" : 1,
                "wysiwyg-color-fuchsia" : 1,
                "wysiwyg-color-green" : 1,
                "wysiwyg-color-lime" : 1,
                "wysiwyg-color-olive" : 1,
                "wysiwyg-color-yellow" : 1,
                "wysiwyg-color-navy" : 1,
                "wysiwyg-color-blue" : 1,
                "wysiwyg-color-teal" : 1,
                "wysiwyg-color-aqua" : 1,
                "wysiwyg-color-orange" : 1
            },
            tags: {
                "b":  {},
                "i":  {},
                "br": {},
                "ol": {},
                "ul": {},
                "li": {},
                "h1": {},
                "h2": {},
                "h3": {},
                "blockquote": {},
                "u": 1,
                "img": {
                    "check_attributes": {
                        "width": "numbers",
                        "alt": "alt",
                        "src": "url",
                        "height": "numbers"
                    }
                },
                "a":  {
                    check_attributes: {
                        href:   "url", // important to avoid XSS
						"target":"alt",
						"rel":"alt",
						"data-placement":"alt",
						"data-original-title":"allow",
						"class":"alt"
                    }
                },
                "iframe": {
                    "check_attributes": {
                        "src":"url",
                        "width":"numbers",
                        "height":"numbers"
                    },
                    "set_attributes":{
                        "frameborder":"0"
                    }
                },
                "span": 1,
                "div": 1,
				"abbr": {
					"check_attributes": {
                        "title":"allow"
                    }
				}
            }
        },
        stylesheets: ["./lib/css/wysiwyg-color.css"], // (path_to_project/lib/css/wysiwyg-color.css)
		fileuploadcallback: 'lalalal',
        locale: "en"
    };

    if (typeof $.fn.wysihtml5.defaultOptionsCache === 'undefined') {
        $.fn.wysihtml5.defaultOptionsCache = $.extend(true, {}, $.fn.wysihtml5.defaultOptions);
    }

    var locale = $.fn.wysihtml5.locale = {
        en: {
            font_styles: {
                normal: "Normal text",
                h1: "Heading 1",
                h2: "Heading 2",
                h3: "Heading 3"
            },
            emphasis: {
                bold: "Bold",
                italic: "Italic",
                underline: "Underline",
				abbr : "Abbr"
            },
            lists: {
                unordered: "Unordered list",
                ordered: "Ordered list",
                outdent: "Outdent",
                indent: "Indent"
            },
            link: {
                insert: "Insert link",
                cancel: "Cancel"
            },
            image: {
                insert: "Insert image",
                cancel: "Cancel"
            },
            video: {
                insert: "Insert YouTube Video",
                cancel: "Cancel",
                invalid: "invalid video URL"
            },
			tooltip: {
                insert: "Insert Tooltip",
                cancel: "Cancel",
                hidden: "Hidden text",
				shorty: "Short text"
            },
			abbr: {
                insert: "Insert Abbr",
                cancel: "Cancel",
                hidden: "Hidden text"
            },
            html: {
                edit: "Edit HTML"
            },
            colours: {
                black: "Black",
                silver: "Silver",
                gray: "Grey",
                maroon: "Maroon",
                red: "Red",
                purple: "Purple",
                green: "Green",
                olive: "Olive",
                navy: "Navy",
                blue: "Blue",
                orange: "Orange"
            }
        }
    };

}(window.jQuery, window.wysihtml5);


/** Insert Video Functions 
 * 
 */ 

(function(wysihtml5) {
    var NODE_NAME = "IFRAME";

    wysihtml5.commands.insertVideo = {
        /**
         * @example
         *    // either ...
         *    wysihtml5.commands.insertVideo.exec(composer, 'insertVideo', 'http://www.youtube.com/embed/Hx_rRirV2vc');
         *    // ... or ...
         *    wysihtml5.commands.insertVideo.exec(composer, 'insertVideo', { src: 'http://www.youtube.com/embed/Hx_rRirV2vc', width: '560', height: '315' });
         */
        exec: function(composer, command, value) {
            value = typeof(value) === "object" ? value : { src: value };
            var doc   = composer.doc,
                video = this.state(composer),
                i,
                parent;

            if (video) {
                // Video already selected, set the caret before it and delete it
                composer.selection.setBefore(video);
                parent = video.parentNode;
                parent.removeChild(video);

                // and it's parent <a> too if it hasn't got any other relevant child nodes
                wysihtml5.dom.removeEmptyTextNodes(parent);
                if (parent.nodeName === "A" && !parent.firstChild) {
                    composer.selection.setAfter(parent);
                    parent.parentNode.removeChild(parent);
                }

                // firefox and ie sometimes don't remove the video handles, even though the video was removed
                wysihtml5.quirks.redraw(composer.element);
                return;
            }

            video = doc.createElement(NODE_NAME);

            for (i in value) {
                video[i] = value[i];
            }
			console.log(video);
			composer.selection.insertNode(video);
	      	if (wysihtml5.browser.hasProblemsSettingCaretAfterImg()) {
	        	textNode = doc.createTextNode(wysihtml5.INVISIBLE_SPACE);
	        	composer.selection.insertNode(textNode);
	        	composer.selection.setAfter(textNode);
	      	} else {
	        	composer.selection.setAfter(video);
	     	}
        },

        state: function(composer) {
            var doc = composer.doc,
                selectedNode,
                text,
                videosInSelection;

            if (!wysihtml5.dom.hasElementWithTagName(doc, NODE_NAME)) {
                return false;
            }

            selectedNode = composer.selection.getSelectedNode(doc);
            if (!selectedNode) {
                return false;
            }

            if (selectedNode.nodeName === NODE_NAME) {
                // This works perfectly in IE
                return selectedNode;
            }

            if (selectedNode.nodeType !== wysihtml5.ELEMENT_NODE) {
                return false;
            }

            text = composer.selection.getText(doc);
            text = wysihtml5.lang.string(text).trim();
            if (text) {
                return false;
            }

            videosInSelection = composer.selection.getNodes(doc, wysihtml5.ELEMENT_NODE, function(node) {
                return node.nodeName === "IFRAME";
            });

            if (videosInSelection.length !== 1) {
                return false;
            }

            return videosInSelection[0];
        },

        value: function(composer) {
            var video = this.state(composer);
            return video && video.src;
        }
    };
}(wysihtml5));

(function(wysihtml5) {
  wysihtml5.commands.insertEmbedVideo = {
    /**     
     * @example
     *    wysihtml5.commands.insertEmbedVideo.exec(element, "insertEmbedVideo", "<iframe width="560" height="315" src="http://www.youtube.com/embed/dJfSS0ZXYdo" frameborder="0" allowfullscreen></iframe>");
     */
    exec: function(element, command, value) {
      var code = value.src,
	  attributes = {
	    src: wysihtml5.commands.getAttributeValue.exec(code,"src"),
	    width: wysihtml5.commands.getAttributeValue.exec(code,"width"),
	    height: wysihtml5.commands.getAttributeValue.exec(code,"height")
	  },
	  obj = (Object.create) ? Object.create(attributes) : new Object(attributes); //Object.create doesn't work in IE8
      wysihtml5.commands.insertVideo.exec(element, command, obj);
    },

    state: function(element) {
      wysihtml5.commands.insertVideo.state(element);
    },

    value: function(element) {
      wysihtml5.commands.insertVideo.value(element);
    }
  };
}(wysihtml5));

(function(wysihtml5) {
  wysihtml5.commands.getAttributeValue = {
    exec: function (code,attr){
      return code.substring(parseInt(code.indexOf(attr))+attr.length + 2,code.length).split("\" ")[0];
    }  
  };
}(wysihtml5));


(function(wysihtml5) {
  var undef,
      NODE_NAME = "ABBR",
      dom       = wysihtml5.dom;
  
  function _removeFormat(composer, anchors) {
    var length  = anchors.length,
        i       = 0,
        anchor,
        codeElement,
        textContent;
    for (; i<length; i++) {
      anchor      = anchors[i];
      codeElement = dom.getParentElement(anchor, { nodeName: "code" });
      textContent = dom.getTextContent(anchor);

      // if <a> contains url-like text content, rename it to <code> to prevent re-autolinking
      // else replace <a> with its childNodes
      if (textContent.match(dom.autoLink.URL_REG_EXP) && !codeElement) {
        // <code> element is used to prevent later auto-linking of the content
        codeElement = dom.renameElement(anchor, "code");
      } else {
        dom.replaceWithChildNodes(anchor);
      }
    }
  }
  
  
  function _format(composer, attributes) {
    var doc             = composer.doc,
        tempClass       = "_wysihtml5-temp-" + (+new Date()),
        tempClassRegExp = /non-matching-class/g,
        i               = 0,
        length,
        anchors,
        anchor,
        hasElementChild,
        isEmpty,
        elementToSetCaretAfter,
        textContent,
        whiteSpace,
        j;
    wysihtml5.commands.formatInline.exec(composer, undef, NODE_NAME, tempClass, tempClassRegExp);
    anchors = doc.querySelectorAll(NODE_NAME + "." + tempClass);
    length  = anchors.length;
    for (; i<length; i++) {
      anchor = anchors[i];
      anchor.removeAttribute("class");
      for (j in attributes) {
        anchor.setAttribute(j, attributes[j]);
      }
    }

    elementToSetCaretAfter = anchor;
    if (length === 1) {
      textContent = dom.getTextContent(anchor);
      hasElementChild = !!anchor.querySelector("*");
      isEmpty = textContent === "" || textContent === wysihtml5.INVISIBLE_SPACE;
      if (!hasElementChild && isEmpty) {
        dom.setTextContent(anchor, attributes.text || anchor.href);
        whiteSpace = doc.createTextNode(" ");
        composer.selection.setAfter(anchor);
        composer.selection.insertNode(whiteSpace);
        elementToSetCaretAfter = whiteSpace;
      }
    }
    composer.selection.setAfter(elementToSetCaretAfter);
  }
  
  wysihtml5.commands.createAbbr = {
    /**
     * 
     * @example
     *    // either ...
     *    wysihtml5.commands.createAbbr.exec(composer, "createAbbr", "sometextfortitle");
     */
    exec: function(composer, command, value) {
      var anchors = this.state(composer, command);
      if (anchors) {
        // Selection contains links
        composer.selection.executeAndRestore(function() {
          _removeFormat(composer, anchors);
        });
      } else {
        // Create links
        value = typeof(value) === "object" ? value : { href: value };
        _format(composer, value);
      }
    },

    state: function(composer, command) {
      return wysihtml5.commands.formatInline.state(composer, command, "ABBR");
    },

    value: function() {
      return undef;
    }
  };
})(wysihtml5)