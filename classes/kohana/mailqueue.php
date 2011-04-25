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
	public static function add_to_queue($recipient, $sender, $subject, $body, $priority = 1)
	{
		Model_MailQueue::add_to_queue($recipient, $sender, $subject, $body, $priority);
		return true;
	}
	
	
	/**
	 * Send out a batch of emails. The number sent is dependant on config('mailqueue.batch_size')
	 *
	 * @return 	array 	The number of emails sent and failed.
	 */
	public static function batch_send()
	{
		$config = kohana::config('mailqueue');
		
		$stats = array('sent' => 0, 'failed' => 0);
		
		$emails = Model_MailQueue::find_batch($config->batch_size);
		foreach($emails as $email)
		{
			$recipient = $email->recipient_email;
			if($email->recipient_name != null)
			{
				$recipient = array($email->recipient_email, $email->recipient_name);
			}
			
			$sender = $email->sender_email;
			if($email->sender_name != null)
			{
				$sender = array($email->sender_email, $email->sender_email);
			}
			
			if(email::send($recipient, $sender, $email->subject, $email->body, true))
			{
				$email->sent();
				$stats['sent'] ++;
			}
			else
			{
				$stats['failed'] ++;
				$email->failed();
			}
		}
		
		return $stats;
	}
}