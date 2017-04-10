

<!DOCTYPE html>
<html lang="<?php //echo $site_lang ?>">
<head>
      <!--base href="<?= base_url() ?><?php echo $site_lang ?>/" /-->
   

   <meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <meta name="description" content="" />
   <meta name="keywords" content="" />
   <meta name="author" content="" />

   <title><?=$title?></title>
   <!-- bootstrap MAIN css -->
   <link href="{SITEURL}/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
   <!-- bootstrap css for dynamic page width -->
   <link href="{SITEURL}/assets/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />
   <!-- bootstrap css for mega icons class viewing -->
   <link href="{SITEURL}/assets/css/font-awesome.css" rel="stylesheet" type="text/css" />
   <!-- customized js -->
   <link href="{SITEURL}/assets/css/custom.css" rel="stylesheet" type="text/css" />
   
   <!-- Just paginator for javascript data (may later be replaced with http://datatables.net/blog/Twitter_Bootstrap_2)-->
   <link href="{SITEURL}/assets/css/jPages.css" rel="stylesheet" type="text/css" />

   <link href="{SITEURL}/assets/css/animate.css" rel="stylesheet" type="text/css" />

   <link href="{SITEURL}/assets/css/prettify.css" rel="stylesheet" type="text/css" />

   <link href="{SITEURL}/assets/css/wysiwyg-color.css" rel="stylesheet" type="text/css" />	
   <link href="{SITEURL}/assets/css/daterangepicker.css" rel="stylesheet" type="text/css" />	
   
   <!-- ?????? ? ????????? -->
   <link href="{SITEURL}/assets/css/datatables/DT_bootstrap.css" rel="stylesheet" type="text/css" /> 
   
   <!--[if lt IE 9]>
   <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
   <script src="{SITEURL}/assets/js/respond.min.js"></script>
   <![endif]-->
   
   <link href="{SITEURL}/assets/css/custom-icamp.css" rel="stylesheet" type="text/css" />
  
   <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
   <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
   <!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
   <script type="text/javascript" src="{SITEURL}/assets/js/jquery/jquery.ui.widget.js"></script>
   <script type="text/javascript" src="{SITEURL}/assets/js/jPages.min.js"></script>
   <script type="text/javascript" src="{SITEURL}/assets/js/bootstrap.min.js"></script>
   <!-- The basic File Upload plugin -->
   <script type="text/javascript" src="{SITEURL}/assets/js/jquery/jquery.fileupload.js"></script>
   <script type="text/javascript" src="{SITEURL}/assets/js/jquery/jquery.iframe-transport.js"></script>
   
   
   <script type="text/javascript" src="{SITEURL}/assets/js/prettify.js"></script>
   
   <script type="text/javascript" src="{SITEURL}/assets/js/bootstrap-tooltip.js"></script>
   <script type="text/javascript" src="{SITEURL}/assets/js/datatables/jquery.dataTables.js"></script>
   <script type="text/javascript" src="{SITEURL}/assets/js/datatables/DT_bootstrap.js"></script>
   <script type="text/javascript" src="{SITEURL}/assets/js/custom.js"></script>
   <script type="text/javascript" src="{SITEURL}/assets/js/ietest7.js"></script>

   <script type="text/javascript" src="{SITEURL}/assets/js/tinymce/tinymce.min.js"></script>

   <script type="text/javascript" src="{SITEURL}/assets/js/date.js"></script>
   <script type="text/javascript" src="{SITEURL}/assets/js/daterangepicker.js"></script>
   <script type="text/javascript" src="{SITEURL}/assets/js/shop.js"></script>
   <script type="text/javascript" src="{SITEURL}/assets/js/fancybox/jquery.mousewheel-3.0.4.pack.js')"></script>
   <script type="text/javascript" src="{SITEURL}/assets/js/fancybox/jquery.fancybox-1.3.4.pack.js')"></script>
   
   <link rel="stylesheet" type="text/css" href="{SITEURL}/assets/js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
</head>
<body>
	<div class="modal hide fade" id="ModalDefault" tabindex="-1" role="dialog" aria-labelledby="ModalDefault" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">?</button>
			<h3 id="myModalLabel"><i class="icon-info-sign"></i> <?php echo lang('ci_base.info');?></h3>
		</div>
		<div class="modal-body"></div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo lang('ci_base.close'); ?></button>
		</div>
	</div>
	<?php if (isset ($modal_messages)) echo $modal_messages; ?>

	<script type="text/javascript">
	$(function() {
		if (window.location.hash.length > 1) {
			$(window.location.hash).modal('show')
        }
        $("a[rel=gallery]").fancybox({
            'transitionIn'    : 'none',
            'transitionOut'   : 'none',
            'titlePosition'   : 'over',
            'titleFormat'     : function(title, currentArray, currentIndex, currentOpts) {
               return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
            }
      });
      $(".item_fotos").click(function(){
         return false;
      });
	})
	</script>

    <div class="page-wrapper">

	<div class="container main-wrapper">
		<div class="row">
			<div class="span8">
				<div class="logo">
					<a href=""><img src="{SITEURL}/assets/img/t-logo.gif" border="0"/></a>
				</div>

			</div>
			<div class="header-right-row span4">
				<ul class="locale-changer">
					<?=language_links('li');?>
				</ul><br/><br/>
				<div class="i-link"><i class="t-yellow-arrow"></i><a href="/"><?=lang('ci_base.gotopromo')?></a></div>
			</div>
		</div>

{CONTENT}




</div>
<!--div class="push"></div-->
</div>


    <div class="buffer"></div>

    </div> <!-- top row-fluid -->

    <div class="row-fluid" style="background: #FAFAF0; padding: 15px 0 40px;border-top: 1px solid #ededed">
<div class="footer container" >
    <? // require '_footer_'.$site_lang.'.php'; ?>
    <div class="row" style="padding-top: 15px; border-top: 1px solid #ddd ">
        <div class="span6">
            <p>(c) i-camp engineering, 2013 | <a href="https://plus.google.com/105833833797834552973" rel="publisher">THRONE on Google+</a></p>
        </div>
        <div class="span5">
                <div class="contacts">Support<?//=lang('ci_base.technical_support')?></div><div class="contacts">Skype: <a href="">throne-bms</a> <br/> e-mail: <a href="mailto:support@throne-bms.com">support@throne-bms.com</a></div>

        </div>
    </div>
</div>

    </div>



    
</body>
</html>
