<?php defined('SYSPATH') or die('No direct script access.');

/**
 * MailQueue exception.
 *
 * @package 	kMailQueue
 * @category  	Exceptions
 * @author 		Alex Gisby <alex@solution10.com>
 */

class Exception_MailQueue extends Exception
{
	public $array = null;
	
	/**
	 * Sets the validation array against itself;
	 *
	 * [!!] We don't type-hint here simply due to the fact that the class is called Validate in 3.0.x and Validation in 3.1.x
	 *
	 * @param 	Validate|Validation 	Validation object
	 * @return 	void
	 */
	public function set_validate_array($array)
	{
		$this->array = $array;
	}
}