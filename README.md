Debug Plugin for WordPress
==========================

Minimalistic debug functions for WordPress. Main course: \__debug

Author: Fabian Wolf
License: GNU GPL v3


Notes on using the native class files
=====================================

If you're planning to publish them at the official WordPress Theme Repository or any other theme directory with similar rules, you should preferably use the light version (`debug-lite.class.php`), which does not use direct file access for logging purposes, but instead relies on the PHP error_log() function. 

According to the official WordPress Repository Rules, you're not allowed to use direct filesystem access. Instead you have to use the WP Filesystem API, which is rather stunted, ie. it's pretty much useless for logging purposes, because their `file_put_contents()` implementation does not support appending data to already existing files.



