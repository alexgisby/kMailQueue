<?php defined('SYSPATH') or die('No direct script access.');

return array(
	
	/**
	 * The number of emails to send out in each batch. This should be tuned to your servers abilities
	 * and the frequency of the cron.
	 */
	'batch_size'	=> 25,
	
	
	/**
	 * The maximum number of attempts to send an email before giving up. An email may fail to send if the
	 * server is too busy, or there's a problem with the email itself.
	 */
	'max_attempts'	=> 5,
	
	
	/**
	 * Passphrase. This is a simple little password you can provide at the end of the cron string to make sure
	 * only you can access the cron.
	 *
	 * So with a passphrase of 'GLaDOS', your url would look like:
	 *		yourdomain.com/mailqueue/batch/GLaDOS
	 *
	 * Set to null to disable.
	 */
	'passphrase'	=> 'GLaDOS',	/************* CHANGE ME *********************/
	
	
	/**
	 * Allowed IP's. You can set an array of IP's which are the only ones allowed to access the mail queue.
	 * Helpful to prevent people knackering your server by hitting the queue over and over.
	 *
	 * Set to an empty array to disable.
	 */
	'allowed_ips'	=> array('127.0.0.1'),
	
);