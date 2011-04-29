<?php defined('SYSPATH') or die('No direct script access.');

/**
 * MailQueue Body Model. Contains the actual content of the email. Splitting them off improves performance.
 *
 * @package 	MailQueue
 * @category  	Models
 * @author 		Alex Gisby <alex@solution10.com>
 */

class Kohana_Model_MailQueue_Body extends ORM
{
	protected $_table_name = 'mailqueue_bodies';
	
	protected $_rules = array(
		'body'			=> array(
			'not_empty'		=> null,	
		),
	);
	
	
	/**
	 * Kohana 3.1.x rules hook. This is ignored by Kohana 3.0.x
	 */
	public function rules()
	{
		return array(
			'body' => array(
				array('not_empty'),
			),
		);
	}
	
	
	/**
	 * Adds the body to this table from the Queue
	 *
	 * @param 	Model_MailQueue 	Queue 'Header' Model
	 * @param 	string 				Body of the email
	 * @return 	Model_MailQueue_Body
	 */
	public static function add_email_body(Model_MailQueue $header, $body = null)
	{
		$bmodel 			= ORM::factory('MailQueue_Body');
		$bmodel->queue_id 	= $header->id;
		$bmodel->body 		= $body;
		
		if(version_compare(kohana::VERSION, '3.1', '>='))
		{
			//
			// 3.1.x workflow:
			//
			try
			{
				$bmodel->save();
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
			if($bmodel->check())
			{
				$bmodel->save();
			}
			else
			{
				$ex = new Exception_MailQueue('Failed Validation');
				$ex->set_validate_array($bmodel->validate());
				throw $ex;
			}
		}
	}
}