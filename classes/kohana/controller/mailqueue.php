<?php defined('SYSPATH') or die('No direct script access.');

/**
 * This controller is where your CRON should point, as well as providing some simple tests.
 *
 * @package 	kMailQueue
 * @category  	Controllers
 * @author 		Alex Gisby <alex@solution10.com>
 */

class Kohana_Controller_MailQueue extends Controller
{
	/**
	 * The action your CRON should be looking at
	 */
	public function action_batch()
	{
		MailQueue::batch_send();
		return true;
	}
	
	
	/**
	 * Some basic tests and usage examples
	 */
	public function action_test()
	{
		echo '<h1>Basic Email</h1>';
		
		MailQueue::add_to_queue(
			array('alex@solution10.com', 'Alex'),
			array('alex@solution10.com', 'Alex'),
			'Mail with both sender and recipient as array',
			'<p>Welcome to the MailQueue App</p>',
			10
		);
		
		echo '<p>Added Mail 1</p>';
		
		MailQueue::add_to_queue(
			'alex@solution10.com',
			'alex@solution10.com',
			'Mail with strings for recip and sender',
			'<p>Welcome to the MailQueue App</p>',
			10
		);
		
		echo '<p>Added Mail 2</p>';
		
		echo '<h1>Validation Checks</h1>';
		
		try
		{
			MailQueue::add_to_queue(
				array('pants_recipient', 'Mr Pants'),
				array('pants_sender', 'Mrs Pants'),
				'Pants array',
				'<p>Welcome to the MailQueue App</p>'
			);
		}
		catch(Exception_MailQueue $e)
		{
			echo Kohana::debug($e->array->errors());
		}
		
		try
		{
			MailQueue::add_to_queue(
				'pants_recipient',
				'pants_sender',
				'Pants string',
				'<p>Welcome to the MailQueue App</p>'
			);
		}
		catch(Exception_MailQueue $e)
		{
			echo Kohana::debug($e->array->errors());
		}
		
		exit('End Test');
	}

}