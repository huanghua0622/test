<?php

	$mem = new memcache();
	$mem->connect('127.0.0.1', 12000);

	$true = true;

	$mem->set('true', $true, 0, 0);
	$result = $mem->get('true');

	var_dump($result);

	echo '<hr />';

	$false = false;

	$mem->set('false', $false, 0, 0);
	$result = $mem->get('false');

	var_dump($result);