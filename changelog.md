# kMailQueue Changelog

## 1.2

- Added support for Kohana 3.2.
- Removed the dependency on the Kohana Email module which now doesn't work under 3.2.

## 1.1

- Moved the email bodies into their own separate table. **NOTE** If you were using 1.0, this change will break your Queue. You'll need to move the emails into the new table.

## 1.0

Initial release.

- Sending by priority
- IP lockdown
- Passphrase
- Validation of emails