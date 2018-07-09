# WebNotifier
WebNotifier is a small utility (written in PHP) that helps you to monitor changes in a web page.

WebNotifier reads the html code of a web page and searches for a regular expression on html code.
If match is found (or not found) it will send an email message to your address.

WebNotifier is usable when combined with cron / task scheduler / etc. to monitor
a web page continuously. It can detect when an enrollment to a course starts, when the
consert tickets are available, when the price at auction page changes, etc.

Syntax to run WebNotifier is
'php webnotifier.php {properties-file-name}'

if properties-file-name is omitted, default properties file webnotify.properties is used

Properties file is a text file containing key=value -pairs:

* url : url address. The url address of the web page to monitor. Protocol can be aither http or https.
* key : regex. The regular expression that will be searched in the html code
* match : true | false. If key is found in url and match is true, email is sent. If key is __not found__ and match is false email is sent.
* mail : email address. The address where the mail is sent. WebNotifier uses default PHP mail function which assumes mail server configuration is done in your php.ini
* cookie : a cookie to set. The value of this key is cookie-name=cookie-value (e.g. cookie=JSESSION=123abc567). Only one cookie can be set. This key is optional.

Below is an example of a properties file. This monitors if text "price $100" is not found in http://some-auction-page.com/?item=123. When then price on the page changes, new mail is sent to me@mymail.com

url=http://some-auction-page.com/?item=123
key=price $100
match=false
mail=me@mymail.com
