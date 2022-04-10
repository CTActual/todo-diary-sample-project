<?php

// This uses string code and other tricks to set the number of significant digits of a float

class use_sigf_dgts
{
	public $num;				# the input number
	public $sd;					# the number of significant digits to apply
	public $return;				# the return value

	function __construct($num=null, $sd=null)
	{
		// We've changed the default number of significant digits to 3 from 6.
		if ($num === NULL || !is_numeric($num) || $num == 0) {$this->return = $num; return $num;} else {$this->num = (float) $num;}
		if (!check_index($sd) ) {$this->sd = 3;} else {$this->sd = (int) floor($sd);}

		$factor = 1;
		if ($this->num < 0) {$factor = -1; $this->num = -1*$this->num;}

		$this->return = $factor*round(pow(10, (log10($this->num) - floor(log10($this->num)))), $this->sd - 1)*pow(10, floor(log10($this->num)));

		if (strrpos($this->return, "999999") == true || strrpos($this->return, "000001") == true || strrpos($this->return, "00000000") == true) 
		{
			if ((float) abs($this->return) >= 1)
			{
				// Size of the floating point value to the decimal place (3 places for 999 of 999.999)
				$floor_size = strlen(floor($this->return) );

				// Amount after the decimal place for sd purposes (3 places from 999.999 is sd = 6)
				$round_floating_part_size = (int) ($this->sd - $floor_size);

				// Round the value after making the number the proper number of digits bigger (999999)
				$rounded_value = (int) round(($this->return)*pow(10, $round_floating_part_size), 0);

				if ($this->sd == 1)
					{$this->return = substr($rounded_value, 0, $floor_size);}
				else
					{$this->return = substr($rounded_value, 0, $floor_size) . "." . substr($rounded_value, $floor_size);}
				}
			else
			{
				// Determine the required number of decimal places
				$round_floating_part_size = (int) ($this->sd - 1 - floor(log10($this->return)));

				$rounded_value = (int) round(($this->return)*pow(10, $round_floating_part_size), 0);

				$zero_pad_size = $round_floating_part_size - strlen($rounded_value);
			
				$zero_pad = str_pad("", $zero_pad_size, "0");

				$this->return = "0." . $zero_pad . substr($rounded_value, 0, $round_floating_part_size);
				}
			}

		unset($factor);
		unset($floor_size);
		unset($round_floating_part_size);
		unset($rounded_value);
		unset($zero_pad_size);
		unset($zero_pad);

		} # End of construct function

	function __destruct()
	{
		unset($this->num);
		unset($this->sd);
		unset($this->return);
		}

	} # End of class

//______________________________________________________________________________________________________
function use_sigf_dgts_function($num=null, $sd=null)
{
	$sd_cls = new use_sigf_dgts($num, $sd);
	$end_val = (string) $sd_cls->return;
	unset($sd_cls);

	return $end_val;
	}	# End of use_sigf_dgts_function

//______________________________________________________________________________________________________
function trim_decimals($num=null, $zeroes=0)
{
	if (empty($num) || !is_numeric($num) ) {return $num;}

	if ($num < 0) {$func = 'ceil';} else {$func = 'floor';}

	$new = (string) $func($num*(pow(10, $zeroes) ) );

	if (stripos($new, '.') !== false) {$new2 = substr($new, 0, stripos($new, '.') );} else {$new2 = $new;}

	if (strlen($new2) > 0)
	{
		if ($zeroes > 0)
		{
			$new2_cut = min(array($zeroes, strlen($new2) ) );

			$sleft = substr($new2, 0, -1*$new2_cut);
			$sright = str_repeat('0', abs($zeroes - $new2_cut) ) . substr($new2, -1*$zeroes);

			if ($sleft === '') {$sleft = "0";}

			if ($sleft === '-') {$sctr = '0.';} else {$sctr = '.';}

			return $sleft . $sctr . $sright;
			}
		else
		{
			return $new2 . str_repeat('0', abs($zeroes) );
			}
		}
	else
	{return null;}
	}

//______________________________________________________________________________________________________
$usdf = 'use_sigf_dgts_function';
$trimd = 'trim_decimals';

?>
