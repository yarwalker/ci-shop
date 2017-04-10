<div class="row" style="padding-top:40px;">
	<script type="text/javascript">
	$(function() {
		if (window.location.hash.length > 1) {
            //var id  = window.location.hash.replace(/^#/, '');
			$(window.location.hash).show();
        }
	})
	</script>
	<div class="span9 main">
		<div class="alert alert-success" id="register_success" style="display:none;"><?php echo lang('ci_main.register_success')?></div>
		<div class="alert alert-success" id="feedback_success" style="display:none;"><?php echo lang('ci_main.feedback_success')?></div>
		<div class="alert alert-success" id="feedback_errors"  style="display:none;"><?php echo lang('ci_main.feedback_errors')?></div>
		<div class="alert alert-success" id="invite_success"  style="display:none;"><?php echo lang('ci_main.invite_success')?></div>
		<div class="alert alert-success" id="account_success"  style="display:none;"><?php echo lang('ci_main.account_success')?></div>
		<div class="alert alert-success" id="new_password_success"  style="display:none;"><?php echo lang('ci_main.new_password_success')?></div>
		<div class="row">
		    <div class="span9">
                <? if($is_logged)
                     echo isset($page_content->auth_body) ? stripslashes(htmlspecialchars_decode($page_content->auth_body, ENT_QUOTES)) : '';
                   else
                     echo isset($page_content->body) ? stripslashes(htmlspecialchars_decode($page_content->body, ENT_QUOTES)) : '';
                ?>

                <!--h1><?=lang('ci_main.h1')?></h1>
			    <h2><?=lang('ci_main.h2')?> <?=lang('ci_main.h3')?></h2>
			    <p class="get-started"><?=lang('ci_main.get_started')?></p-->
			
			    <?php if ( $is_logged ) :?>
				    <p class="get-started"><?=lang('ci_main.registered')?></p>
				    <br/>
				    <div class="i-link"><i class="t-yellow-arrow"></i>
                        <?=anchor('pages/tstudio', lang('ci_main.launch_t_studio'), 'title="' . lang('ci_main.launch_t_studio') . '"');?>
                    </div>
                    <br/>
				    <div class="i-link"><i class="t-yellow-arrow"></i><?php echo anchor(lang('ci_base.tstudio_manual'), lang('ci_main.open_t_studio_manual'), array ('target'=>'blank')) ?></div><br/>
				    <div class="i-link"><i class="t-yellow-arrow"></i><?php echo anchor(language_code() . '/pages/throne_price', lang('ci_main.throne_software_and_hardware_pricelist'), array ('target'=>'blank')) ?></div><br/>
				
				
				    <?php echo form_open('cabinet/feedback',array('class'=>"form-inline")); ?>
				    <?
				    echo form_textarea_group(
					    array(
						    'type'=>'text',
						    'class'=>'span5',
						    'name'=>'mailbody',
						    'id'=>'input_comments',
						    'maxlength'=>'1000'
						    ),
					        set_value('Comments'),
					        lang('ci_main.for_feedback')
				        );
				    ?>
				    <button type="submit" class="btn"><?=lang('ci_base.send')?></button>
				    <?=form_close();?>
			    <?php endif; ?>

			<!--div class="empty_space"></div-->
		    </div>
        </div>
	</div>