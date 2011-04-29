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
	
	protected $_has_one = array(
		'body'	=> array(
			'model'			=> 'MailQueue_Body',
			'foreign_key'	=> 'queue_id',
		),
	);
	
	protected $_rules = array(
		'recipient_email'	=> array(
			'not_empty'		=> null,
			'email'			=> null,
		),
		
		'sender_email'	=> array(
			'not_empty'		=> null,
			'email'			=> null,
		),
	);
	
	/**
	 * Kohana 3.1.x rules hook. This is ignored by Kohana 3.0.x
	 */
	public function rules()
	{
		return array(
			'recipient_email' => array(
				array('not_empty'),
				array('email'),
			),
			'sender_email' => array(
				array('not_empty'),
				array('email'),
			),
			'body' => array(
				array('not_empty'),
			),
		);
	}
	
	
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
		
		if(version_compare(kohana::VERSION, '3.1', '>='))
		{
			//
			// 3.1.x workflow:
			//
			try
			{
				$item->save();
			}
			catch(ORM_Validation_Exception $e)
			{
				$ex = new Exception_MailQueue('Failed Validation');
				$objects = $e->objects();
				$ex->set_validate_array($objects['_object']);
				throw $ex;
			}
		}
		else
		{
			//
			// 3.0.x workflow:
			//
			if($item->check())
			{
				$item->save();
			}
			else
			{
				$ex = new Exception_MailQueue('Failed Validation');
				$ex->set_validate_array($item->validate());
				throw $ex;
			}
		}
		
		return $item;
	}
	
	
	/**
	 * Gets a batch of emails ready for sending
	 *
	 * @param 	int 	Number of emails to send (batch size)
	 * @return 	ORM		Collection of objects
	 */
	public static function find_batch($batch_size)
	{
		return ORM::factory('MailQueue')
						->where('state', '=', 'pending')
						->order_by('priority', 'DESC')
						->order_by('created', 'ASC')
						->limit($batch_size)
						->find_all();
	}
	
	
	/**
	 * Called when an email has been sent
	 *
	 * @return 	this
	 */
	public function sent()
	{
		$this->sent 	= date('Y-m-d H:i:s', time());
		$this->state	= 'sent';
		$this->attempts++;
		$this->save();
		
		return $this;
	}
	
	
	/**
	 * Called when an email fails. If it's hit the limit of
	 *
	 * @return 	this
	 */
	public function failed()
	{
		$this->attempts ++;
		
		if(kohana::config('mailqueue.max_attempts') <= $this->attempts)
		{
			$this->state 	= 'failed';
			$this->failed	= date('Y-m-d H:i:s', time());
		}
		
		$this->save();
		return $this;
	}
}