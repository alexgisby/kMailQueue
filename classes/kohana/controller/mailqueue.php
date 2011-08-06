<?php defined('SYSPATH') or die('No direct script access.');

/**
 * This controller is where your CRON should point, as well as providing some simple tests.
 *
 * @package 	kMailQueue
 * @category  	Controllers
 * @author 		Alex Gisby <alex@example.com>
 */

class Kohana_Controller_MailQueue extends Controller
{
	/**
	 * The action your CRON should be looking at
	 */
	public function action_batch()
	{
		if(version_compare(kohana::VERSION, '3.2.0', '>='))
		{
			$config = kohana::$config->load('mailqueue');
		}
		else
		{
			$config = kohana::config('mailqueue');
		}
		
		// Check they're from an allowed IP;
		$allowed_ips 	= $config->allowed_ips;
		$passphrase 	= $this->request->param('id');
		if(($config->passphrase != null && $passphrase != $config->passphrase) || (!empty($allowed_ips) && !in_array(Request::$client_ip, $allowed_ips)))
		{
			if(version_compare(kohana::VERSION, '3.1', '>='))
			{
				$this->response->status		= 403;
				$this->response->body('Not Allowed');
			}
			else
			{
				$this->request->status 		= 403;
				$this->request->response 	= 'Not Allowed';
			}
		}
		else
		{
			$stats = MailQueue::batch_send();
			
			if(version_compare(kohana::VERSION, '3.1', '>='))
			{
				$this->response->body(
					'Sent ' . $stats['sent'] . ' emails' . "\n" . 'Failed ' . $stats['failed'] . ' emails' . "\n"
				);
			}
			else
			{
				$this->request->response .= 'Sent ' . $stats['sent'] . ' emails' . "\n";
				$this->request->response .= 'Failed ' . $stats['failed'] . ' emails' . "\n";
			}
		}
	}
	
	/**
	 * Some basic tests and usage examples
	 */
	public function action_test()
	{
		if(kohana::$environment == kohana::DEVELOPMENT)
		{
			echo '<h1>Basic Email</h1>';

			$first = MailQueue::add_to_queue(
				array('alex@example.com', 'Alex'),
				array('alex@example.com', 'Alex'),
				'Mail with both sender and recipient as array',
				'<p>Welcome to the MailQueue App</p>',
				10
			);

			echo '<p>Added Mail 1</p>';

			MailQueue::add_to_queue(
				'alex@example.com',
				'alex@example.com',
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
			
				echo 'Added mail?';
			}
			catch(Exception_MailQueue $e)
			{
				echo '<pre>' . print_r($e->array->errors(), true) . '</pre>';
			}

			try
			{
				MailQueue::add_to_queue(
					'pants_recipient',
					'pants_sender',
					'Pants string',
					'<p>Welcome to the MailQueue App</p>'
				);
			
				echo 'Added mail?';
			}
			catch(Exception_MailQueue $e)
			{
				echo '<pre>' . print_r($e->array->errors(), true) . '</pre>';
			}
		
		
			try
			{
				MailQueue::add_to_queue(
					'alex@example.com',
					'alex@example.com',
					'Testing No Body Validation',
					''
				);
			}
			catch(Exception_MailQueue $e)
			{
				echo '<pre>' . print_r($e->array->errors(), true) . '</pre>';
			}
				
			echo '<h1>Content Test</h1>';
			echo '<h2>First Email</h2>';
			echo 'To: ' . $first->recipient_name . ' &lt;' . $first->recipient_email . '&gt;<br />';
			echo 'From: ' . $first->sender_name . ' &lt;' . $first->sender_email . '&gt;<br />';
			echo 'Subject: ' . $first->subject . '<br /><br />';
			echo $first->body->body;
			exit('End Test');
		}
	}


}