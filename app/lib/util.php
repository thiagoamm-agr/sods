<?php
	// returns true if $str begins with $sub
	function startsWith($str, $sub) {
		return (substr($str, 0, strlen($sub)) == $sub);
	}

	// return tru if $str ends with $sub
	function endsWith($str, $sub) {
		return (substr($str, strlen($str) - strlen($sub)) == $sub);
	}
?>