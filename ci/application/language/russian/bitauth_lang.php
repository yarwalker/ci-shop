<?php
/**
 * This line is required, it must contain the label for your unique username field (what users login with)
 */
$lang['bitauth_username']			= 'Имя пользователя';
/**
 * Password Complexity Labels
 */
$lang['bitauth_pwd_uppercase']		= 'Верхний регистр';
$lang['bitauth_pwd_number']			= 'Цифры';
$lang['bitauth_pwd_special']		= 'Спецсимволы';
$lang['bitauth_pwd_spaces']			= 'Пробелы';
/**
 * Login Error Messages
 */
$lang['bitauth_login_failed']		= 'Ошибка. %s или пароль не подходит';
$lang['bitauth_user_inactive']		= 'Перед тем, как войти, необходимо активировать аккаунт.';
$lang['bitauth_user_locked_out']	= 'Вас заблокировали на %d минут за слишком частые неудачные попытки логина, попробуйте позже.';
$lang['bitauth_pwd_expired']		= 'Срок действия Вашего пароля истёк.';
/**
 * User Validation Error Messages
 */
$lang['bitauth_unique_username']	= 'Поле %s должно быть уникальным.';
$lang['bitauth_password_is_valid']	= '%s не соответствует требованиям: %s';
$lang['bitauth_username_required']	= 'Поле %s является обязательным.';
$lang['bitauth_password_required']	= 'Поле %s является обязательным.';
$lang['bitauth_passwd_complexity']	= 'Пароль не соответствует требованиям: %s';
$lang['bitauth_passwd_min_length']	= 'Пароль должен быть не меньше %d символов.';
$lang['bitauth_passwd_max_length']	= 'Пароль должен быть не больше %d символов.';
/**
 * Group Validation Error Messages
 */
$lang['bitauth_unique_group']		= 'Поле %s должно быть уникальным.';
$lang['bitauth_groupname_required']	= 'Поле Имя компании является обязательным.';
/**
 * General Error Messages
 */
$lang['bitauth_instance_na']		= "BitAuth не смог получить экземпляр класса CI.";
$lang['bitauth_data_error']			= 'Вы не можете переписать свойства по умолчанию для BitAuth произвольными данными. Пожалуйста, измените имя поля: %s';
$lang['bitauth_enable_gmp']			= 'Необходимо активировать php_gmp для использования Bitauth.';
$lang['bitauth_user_not_found']		= 'Пользователь не найден: %d';
$lang['bitauth_activate_failed']	= 'Не могу активировать пользователя с активационным кодом.';
$lang['bitauth_expired_datatype']	= '$user должно быть массивом или объектом в Bitauth::password_is_expired()';
$lang['bitauth_expiring_datatype']	= '$user должно быть массивом или объектом в Bitauth::password_almost_expired()';
$lang['bitauth_add_user_datatype']	= '$data должно быть массивом или объектом в Bitauth::add_user()';
$lang['bitauth_add_user_failed']	= 'Adding user failed, please notify an administrator.';
$lang['bitauth_code_not_found']		= 'Activation Code Not Found.';
$lang['bitauth_edit_user_datatype']	= '$data должно быть массивом или объектом в Bitauth::update_user()';
$lang['bitauth_edit_user_failed']	= 'Updating user failed, please notify an administrator.';
$lang['bitauth_del_user_failed']	= 'Deleting user failed, please notify an administrator.';
$lang['bitauth_set_pw_failed']		= 'Unable to set user\'s password, please notify an administrator.';
$lang['bitauth_no_default_group']	= 'Default group was either not specified or not found, please notify an administrator.';
$lang['bitauth_add_group_datatype']	= '$data должно быть массивом или объектом в Bitauth::add_group()';
$lang['bitauth_add_group_failed']	= 'Adding group failed, please notify an administrator.';
$lang['bitauth_edit_group_datatype']= '$data должно быть массивом или объектом в Bitauth::update_group()';
$lang['bitauth_edit_group_failed']	= 'Updating group failed, please notify an administrator.';
$lang['bitauth_del_group_failed']	= 'Deleting group failed, please notify an administrator.';
$lang['bitauth_lang_not_found']		= '(No language entry for "%s" found!)';
# for THRONE BMS
$lang['as_admin']					= 'Войти как администратор';
$lang['incorrect_admin']		 	= 'пароль администратора не корректен или администратор не существует';
