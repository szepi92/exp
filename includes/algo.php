<?php

/* Miscellaneous / useful algorithms */

class Algo {

	// Number theory functions
	static public function Factorize($n) {
		$ans = array();
		for ($i = 2; $i*$i <= $n; ++$i) {
			if ($n % $i) continue;
			$pw = 0;
			while (!($n % $i)) {
				$n /= $i;
				$pw++;
			}
			$ans[] = array($i,$pw);
		}
		if ($n > 1) $ans[] = array($n,1);
		return $ans;
	}
	
	static public function Primes($n) {
		$pf = self::Factorize($n);
		$ans = array();
		foreach ($pf as $v) $ans[] = $v[0];
		return $ans;
	}
}