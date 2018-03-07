# Social-Network
A simple Social Network implemented using PHP, AJAX and JSON.
It is responsive, meaning that it looks well in every screen size, from smartphones to large desktops.

Τhis particular Social Network consists of:

A login/register page where passwords are being hashed before they are inserted in the database.
A profile page unique for each user, that is divided into three columns (for large screens).
  The left column holds the friend requests, the middle holds the user posts and the right holds all the friends with whom
  the user can chat with.
A settings page where the user can change his password and his avatar.
A search area on the top bar where by typing characters, the user can search for other registered users and visit their profiles.
A logout option.

Everything apart from the login/register page is implemented using asynchronous programming techniques and more precisely using AJAX.
With AJAX a Social Network becomes more of a real-time application, although the best way to implement a Social Network is using
Web Sockets.
By calling particular JavaScript methods (with AJAX help) every once in a while the data can be transferred between the client and the 
server without the need of reloading the page. That makes the app significantly faster.
