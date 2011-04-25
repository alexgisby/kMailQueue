<?php defined('SYSPATH') or die('No direct script access.');

/**
 * MailQueue Model. Used for interacting with the database.
 *
 * @package 	kMailQueue
 * @category  	Models
 * @author 		Alex Gisby <alex@solution10.com>
 */

class Kohana_Model_MailQueue extends ORM
{
	/**
	 * Adds an email to the Queue
	 *
	 * @param 	string|array 	Recipient. Either email, or array(email, name)
	 * @param 	string|array 	Sender. Either email or array(email, name)
	 * @param 	string			Subject
	 * @param 	string 			Body
	 * @param 	int 			Priority (1 is low, 1,000 is high etc)
	 * @return 	Model_MailQueue
	 * @throws 	Exception_MailQueue
	 */
	public static function add_to_queue($recipient, $sender, $subject, $body, $priority = 1)
	{
		$item = ORM::factory('MailQueue');
		
		if(is_array($recipient) && count($recipient) == 2)
		{
			$item->recipient_name 	= $recipient[0];
			$item->recipient_email	= $recipient[1];
		}
		
		if(is_array($sender) && count($sender) == 2)
		{
			$item->sender_name 	= $sender[0];
			$item->sender_email	= $sender[1];
		}
		
		$item->subject 	= $subject;
		$item->body 	= $body;
		$item->priority	= $priority;
		$item->created 	= date('Y-m-d H:i:s', time());
		$item->save();
		return $item;
	}
}