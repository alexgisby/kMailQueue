#kMailQueue

Simple Mail Queue for Kohana 3.x which utilises a database table, connected with ORM.

## Requirements

- Kohana 3.x (3.0, 3.1 or 3.2)
- Kohana ORM

## Features

- Send out emails in batches (batch size configurable)
- Can assign a priority to outbound emails.
- Validates email addresses on entry
- Kills emails after a defined number of fails
- Restrict access to batch via
	- Passphrase
	- IP address
	
## Installation

Download from here or add as a submodule;
	
	git submodule add https://github.com/alexgisby/kMailQueue.git modules/kMailQueue
	
Enable in your bootstrap:
	
	kohana::modules(array(
		...
		'kMailQueue'	=> MODPATH.'kMailQueue',
		...
	));
	
Make sure the /mailqueue route isn't obscured by any of yours.

## Database schema

Check the schema.sql file in the repo. Feel free to change indexes, engine etc.

kMailQueue contains two tables, one for the header information (sender, recipient, priority etc). The other contains the body of the emails. This split allows for better performance when querying the header table.

## Usage

### Adding to the Queue

To add an email to the queue, simply do;

	MailQueue::add_to_queue(
		array('alex@example.com', 'Alex'),				// Recipient of the email
		array('alex@example.com', 'Alex'),				// Sender of the email
		'I was sent via a MailQueue!',					// Subject line
		'<p>Welcome to the work of MailQueue!</p>',		// Body of the email
		10												// Priority. 1 is low priority, 10 is higher. Can be any UNSIGNED INT.
	);
	
### Sending the Queue
	
Emails are sent out via a Cron job. You should set up a cron to run as often as you think your server can handle and point it at;

	yourdomain.com/mailqueue/batch
	
### Security

MailQueues are open to abuse from people (D)DOS-ing. You can secure your queue with one or both of;

#### A passphrase

Set a passphrase in the config, to mean the cron URL becomes;
	
	yourdomain.com/mailqueue/batch/<passphrase>
	
#### IP lockdown

You can also provide a list of IP addresses in the config which are the only ones allowed to access the Queue.

## API Docs

Check out the API browser in the Userguide module. The class is fully documented, as is the config file. Have a poke about.

## Known Issues

- Nothing so far. Found an issue? [Report It!](https://github.com/alexgisby/kMailQueue/issues)

## Acknowledgements;

For the sender and recipient lengths: http://www.marketingtechblog.com/technology/valid-email-address-length/
For subject length: http://stackoverflow.com/questions/1592291/what-is-the-email-subject-length-limit

## Useful?

If you found this module useful, consider sending me a friendly email (alex@solution10.com).