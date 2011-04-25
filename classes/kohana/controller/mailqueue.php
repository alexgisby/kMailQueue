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
	public function action_test()
	{
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
		
		exit('End Test');
	}

}