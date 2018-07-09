# WebNotifier
WebNotifier is a small utility (written in PHP) that helps you to monitor changes in a web page.

WebNotifier reads the html code of a web page and searches for a regular expression on html code.
If match is found (or not found) it will send an email message to your address.

WebNotifier is usable when combined with cron / task scheduler / etc. to monitor
a web page continuously. It can detect when an enrollment to a course starts, when the
consert tickets are available, when the price at auction page changes, etc.

Syntax to run WebNotifier is
'php webnotifier.php {properties-file-name}

if properties-file-name is omitted, default properties file webnotify.properties is used




