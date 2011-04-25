<?php defined('SYSPATH') or die('No direct script access.');

/**
 * MailQueue Main Class.
 *
 * @package 	kMailQueue
 * @category  	Core
 * @author 		Alex Gisby <alex@solution10.com>
 */

class Kohana_MailQueue
{
	/**
	 * Adds an email to the Queue
	 *
	 * @param 	string|array 	Recipient. Either email, or array(email, name)
	 * @param 	string|array 	Sender. Either email or array(email, name)
	 * @param 	string			Subject
	 * @param 	string 			Body
	 * @param 	int 			Priority (1 is low, 1,000 is high etc)
	 * @return 	bool
	 */
	public static function add_to_queue($recipient, $sender, $subject, $body, $priority)
	{
		Model_MailQueue::add_to_queue($recipient, $sender, $subject, $body, $priority);
		return true;
	}
}