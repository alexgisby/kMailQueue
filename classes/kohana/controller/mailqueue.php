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
		// Check they're from an allowed IP;
		$allowed_ips = kohana::config('mailqueue.allowed_ips');
		$passphrase = $this->request->param('id');
		if((kohana::config('mailqueue.passphrase') != null && $passphrase != kohana::config('mailqueue.passphrase')) || (!empty($allowed_ips) && !in_array(Request::$client_ip, $allowed_ips)))
		{
			$this->request->status 		= 403;
			$this->request->response 	= 'Not Allowed';
		}
		else
		{
			$stats = MailQueue::batch_send();
			$this->request->response .= 'Sent ' . $stats['sent'] . ' emails' . "\n";
			$this->request->response .= 'Failed ' . $stats['failed'] . ' emails' . "\n";
		}
	}

}