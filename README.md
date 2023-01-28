## Working demo
https://articexploit.xyz:8443/demo/

## How it works
The form in the html page takes the imput from the user, a php scripts gets the input processes it and writes it to a json. Back on the html page some javascript runs to display al the data from the json file.

## Features
This implementation has a few basic features.

- **anon name substitution**: if the php code detects that no name was input it automatically defaults to using anon.
- **empty or duplicate message detection**: if the php code detects that the message input is empty or a duplicate from another message it won't write anything to the json file.
- **rudimentary reply function**: the php will always write 2 empty data points in the json file for every entry, the "rname" and "rmessage" datapoints. These can be used by the webmaster to reply to comments by writing directly writing in the json file. The javascript code will also automatically hide the whole div the reply is in if it detects that the reply data points are empty.

## Implementation into your website
The only thing you need to figure out on your own is the css. The javascript creates 2 divs: one for the comment and another one if there is a reply to be displayed. The first one has the class "comment" the second has the class "reply", use those in the css to have proper formatting.

Other than that, simply download and put the files in the directory of your web server where you want the guestbook to be (can also be used as a comment sections). 
