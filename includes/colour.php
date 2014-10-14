<?php

/* Functions dealing with COLOURS!!! */
	
class Colour {
	public $name = "";
	public $primes = array();
	public $total_freq = 0;
	
	public static $COLOUR_MAP = array(
		"naples yellow"			=> "#ffcc67",
		"burnt sienna"			=> "#961a10",
		"alizarin crimson"		=> "#8d0303",
		"vermillion"			=> "#d52927",
		"cadmium yellow medium"	=> "#f2c800",
		"cadmium yellow light"	=> "#f9ee00",
		"oxide of chrome"		=> "#517c34",
		"viridian"				=> "#02a087",
		"light blue"			=> "#007f95",
		"prussian blue"			=> "#003153",
		"ultramarine violet"	=> "#512d5b",
	);
	
	public function __construct($name) {
		$this->name = $name;
	}
	
	// Given a colour list and a sequence of prime-factorization lists
	// Will return array(map from prime->colour_name, map from colour_name->Colour Object)
	static public function AssignColours($colours, $prime_fac_list) {
		// Compute freq[p] := number of times prime p occurs (over all primes)
		$freq = array();
		foreach ($prime_fac_list as $list) {
			$seen = array();
			foreach ($list as $prime) {
				if (array_key_exists($prime,$seen)) continue;
				$seen[$prime] = true;
				if (!array_key_exists($prime,$freq)) $freq[$prime] = 0;
				$freq[$prime]++;
			}
		}
		
		// We now get and sort them (descending) by frequency (we get prime,frequency pairs)
		$sorted_pairs = array();
		foreach($freq as $prime => $occs) {
			$sorted_pairs[] = array($prime,$occs);
		}
		usort($sorted_pairs, array(__CLASS__, "_sort_by_frequency"));
		
		// Compute the sum of frequencies, so we know the average frequency (per colour)
		$num_colours = count($colours);
		$total_freq = 0;
		foreach ($sorted_pairs as $v) { $total_freq += $v[1]; }
		
		// Now assign them. We allow things to go slightly over the average if necessary.
		// This is why order matters. The earlier colours will be slightly
		//   higher than later colours
		$prime_map = array();		// map from prime to colour name
		$colour_map = array();		// map from colour name to colour object
		$i = 0;	// c is cur colour, i is cur prime
		$n = count($sorted_pairs);
		for($c=0;$c<$num_colours;++$c) {
			$colour_name = $colours[$c];
			$avg = $total_freq / (float)($num_colours - $c);
			
			$colour = new Colour($colour_name);
			for(;$i<$n && $colour->total_freq<$avg; ++$i) {
				self::_assign($sorted_pairs[$i][0], $sorted_pairs[$i][1], $colour, $prime_map, $total_freq);
			}
			$colour_map[$colour_name] = $colour;
		}
		
		return array($prime_map, $colour_map);
	}
	
	// Helper functions
	private static function _sort_by_frequency($a, $b) {
		if ($a[1] == $b[1]) return $a[0] - $b[0];
		return $b[1] - $a[1];
	}
	private static function _assign($prime, $freq, &$colour, &$prime_map, &$total_freq) {
		$prime_map[$prime] = $colour->name;
		$colour->total_freq += $freq;
		$colour->primes[] = $prime;
		$total_freq -= $freq;
	}
}