<?php

/**
 * 密码加密
 */
function encrypt($str){
	return md5(config("AUTH_CODE").$str);
}
