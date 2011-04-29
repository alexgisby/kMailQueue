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
}