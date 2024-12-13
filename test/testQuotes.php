<?php

		$resource = "contact";
		$searchListURL = "\"/{$resource}SearchList.class.php\"";
		$action = "onkeyup='refreshList(this, {$searchListURL})'";
		echo $action;
