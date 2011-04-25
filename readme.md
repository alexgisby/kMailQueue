#kMailQueue

Simple Mail Queue for Kohana 3.x which utilises a database table, connected with ORM.

## Requirements

- Kohana 3.x (3.0 or 3.1)
- Kohana ORM
- [Kohana Email extension](https://github.com/banks/kohana-email)

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

## Usage

### Adding to the Queue

To add an email to the queue, simply do;

	MailQueue::add_to_queue(
		array('alex@example.com', 'Alex'),
		array('alex@example.com', 'Alex'),
		'I was sent via a MailQueue!',
		'<p>Welcome to the work of MailQueue!</p>',
		10
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

- It makes sense to split off the email body into a separate table to improve performance. Will do this soon.

## Acknowledgements;

For the sender and recipient lengths: http://www.marketingtechblog.com/technology/valid-email-address-length/
For subject length: http://stackoverflow.com/questions/1592291/what-is-the-email-subject-length-limit

## Useful?

If you found this module useful, consider sending me a friendly email (alex@solution10.com).