## Working demo
https://articexploit.xyz/demo/

## Features
This implementation has a few basic features.

- **anon name substitution**: if the php code detects that no name was input it automatically defaults to using anon.
- **empty or duplicate message detection**: if the code detects that the message input is empty or a duplicate from another message it won't write anything to the json file.
- **rudimentary reply function**: the php will always write 2 empty items in the json file for every submission object, the "rname" and "rmessage" items. These can be used by the webmaster to reply to comments by writing directly writing in the json file. The code will also automatically hide the whole div the reply is in, if it detects that the reply items are empty.
- **captcha system**: users will have to solve a captcha to post a message, makes it so the guestbook won't be as spammable. (this is an adapted version of the one developed by hnxh you can check it out [here](https://github.com/hnhx/captcha))
- **character limit**: the webmaster can set the character limit that commenters can use in their messages, it's done through the variable at the start of [comments.php](https://github.com/ArticExploit/guestbook/blob/main/comments.php) (set to 0 for no limit).
- **error messages**: error messages for users: invalid captcha and character limit, these also show the message the user typed out so they won't have to type the whole thing again.

## Installation
- Install php
- Clone/Download the repo
- Place the files in the directory of your webserver where you want the guestbook to be
- Make sure data.json is readable and writable by your webserver

## Implementation into your website
The only thing you need to figure out on your own is the css. The code creates 2 divs: one for the comment and another one if there is a reply to be displayed. The first one has the class "comment" the second has the class "reply", use those in the css to have proper formatting.
Also make sure you're using this at the very top of your php page. for an example see the [index.php](https://github.com/ArticExploit/guestbook/blob/main/index.php) file
```php
<?php
session_start();
?>
```

## How it works
The form in the html page takes the imput from the user, a php scripts gets the input processes it and writes it to a json file. Back on the html page another php script runs to read and display all the data from the json file.

### Bonus :D
This was thought of as a guestbook, but would work just as well as a comment section for a blog for example.

## To Do
A rewrite is needed since this guestbook doesn't allow for replies to replies, nor it does anything for spam protection.
- ~~Add captcha system~~
- ~~Add possibility of char limit~~
- Add time and date to the posted message
- Add new possible fields (email, website)
- Add possibility to use a word blocklist
- Add possibility to create aliases for words
