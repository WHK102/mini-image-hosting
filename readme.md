# Minimalist Image Hosting System

A minimalist image hosting writed in one php script and one database file.


## Advantage

- You do not need a SQL database.
- Prevents restriction problems due to the number of files to be saved.
- Handling browser cache to prevent overloads.


## Howto use?

Upload all content from `public_html` directory to your public folder, and that's it.


## How does it work?

Save all images as binary content into `.ht_dbimages` file, this file is protected automatically by apache because it starts with `.ht`, the script will generate a URL next to the binary position of the image within the storage file.

Example: `http://localhost/100_200.jpg` get the binary content from `.ht_dbimages` from byte 100 to 200, the original internal url is `http://localhost/index.php?data=100_200.jpg`.

Mod rewrite is required or make a specific internal redirection.


## How to contribute?

|METHOD                 |WHERE                                                                                        |
|-----------------------|---------------------------------------------------------------------------------------------|
|Donate                 |[Paypal](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KM2KBE8F982KS) |
|Find bugs              |Using the [Issues tab](https://github.com/WHK102/mini-image-hosting/issues)                  |
|Providing new ideas    |Using the [Issues tab](https://github.com/WHK102/mini-image-hosting/issues)                  |
|Creating modifications |Using the [Pull request tab](https://github.com/WHK102/mini-image-hosting/pulls)             |
