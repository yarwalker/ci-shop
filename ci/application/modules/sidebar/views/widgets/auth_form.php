<?php 

//var_dump_print($_SESSION);

echo form_open( language_code() . '/auth',array('class'=>"well form-inline form-login", 'autocomplete' => 'off' )); ?>
<? if (!isset($_SESSION['logged']) || $_SESSION['logged'] < 1):?>	
		<div class="control-group">
			<label class="control-label"><?=lang('ci_base.login')?></label>
			<div class="controls">
				<input name="username" type="text" value="<?php echo set_value('username')?>" class="input-middle"/>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label"><?=lang('ci_base.paswd')?> </label>
			<div class="controls">
				<input name="password" type="password" class="input-middle"/>
			</div>
		</div>
        <input type="hidden" name="referer_url" value="<?=uri_string()?>" />
		<?php if (isset($_SESSION['login_errors'])) form_errors_fetch($_SESSION['login_errors']);?>
		<button type="submit" class="btn"><?=lang('ci_base.enter')?></button>
        <a class="ch-pass" href="<?=lang_root_url('user/passremind')?>"><?=lang('ci_base.passremind')?></a>
		<br/><br/>

		<div class="i-link"><i class="t-dark-arrow"></i><a href="<?=lang_root_url('company/register')?>"><?=lang('ci_base.register')?></a></div>
		<br/>
		<div class="i-link"><i class="t-dark-arrow"></i><a href="<?=lang_root_url('user/activateaccount')?>"><?=lang('ci_base.activateaccount')?></a></div>
<?endif;?>	
<? if( isset($_SESSION['gobacklink']) )
	echo '<br/><div class="i-link"><i class="t-dark-arrow"></i>' . $_SESSION['gobacklink'] . '</a></div>';
?>
				
	<br/>
	<div class="i-link"><i class="t-dark-arrow"></i><a href="<?=lang_root_url('user/invite')?>"><?=lang('ci_base.invite')?></a></div>
<?=form_close();?>