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
	protected $_table_name = 'mailqueue';
	
	protected $_rules = array(
		'recipient_email'	=> array(
			'not_empty'		=> null,
			'email'			=> null,
		),
		
		'sender_email'	=> array(
			'not_empty'		=> null,
			'email'			=> null,
		),
		
		'body'			=> array(
			'not_empty'		=> null,	
		),
	);
	
	
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
			$item->recipient_email	= $recipient[0];
			$item->recipient_name 	= $recipient[1];
		}
		else
		{
			$item->recipient_email = $recipient;
		}
		
		if(is_array($sender) && count($sender) == 2)
		{
			$item->sender_email	= $sender[0];
			$item->sender_name 	= $sender[1];
		}
		else
		{
			$item->sender_email = $sender;
		}
		
		$item->subject 	= $subject;
		$item->body 	= $body;
		$item->priority	= $priority;
		$item->created 	= date('Y-m-d H:i:s', time());
		
		if($item->check())
		{
			$item->save();
			return $item;
		}
		else
		{
			$ex = new Exception_MailQueue('Failed Validation');
			if(version_compare(kohana::VERSION, '3.1', '>='))
			{
				$ex->set_validate_array($item->validation());
			}
			else
			{
				$ex->set_validate_array($item->validate());
			}
			
			throw $ex;
		}
	}
}