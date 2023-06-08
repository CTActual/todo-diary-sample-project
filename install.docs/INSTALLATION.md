# Installation

If you are familiar with setting up a LAMP server and websites, these instructions will be very obvious.

The outline is as follows (not all the steps for setting up a LAMP server are provided, as they can be found elsewhere).

If you are not a website programmer and are not familiar with these technologies, you will need to get some help with the installation.

1.  You will need a LAMP server, or some equivalent of Apache (Nginx, eg.).  Installation on a BSD or Windows server is also doable.

2.  You will need MySQL on the server.  The install scripts are for version 8.  To use a previous version, you will need to change the collation in the database installation scripts.  There are several options for this.

3.  You will need to set up a website, or website folder for the **todolist** website.

4.  Aside from the readme files and install folders, the **todolist** folder is that website.

5.  Run the two database install scripts.  One is for the **todolist** website database and the other is for the local **HFW** database.

6.  You should change the default database user passwords in the files that contain them: the database user install script, the ***hfw.export.lib/hfw.db.info.php*** file and the ***classes/mysqli.info.php*** file.

7.  Once you are happy with your choice of passwords, and they are consistent amongst the various files, you can run the user install script.

8.  To alter this website, you should probably also install the **Hoopla Framework** code as a separate website on the same server, or at least with a connection to the shared HFW database.  Instructions on how to set up the **Hoopla Framework** UI website are with that project.  This website will work independently of that installation.

9.  You should probably have a MySQL database utility program such as PHPMyAdmin available.  This can often be installed through the common repositories of your Linux distribution.  If you do not have such a utilty then you will need to run the install scripts from the command line.

10.  Make sure the files and folders in your website directory have the correct permissions after copying them.

11. You may need to set up the website in your web-server configuration file.  You should use security to prevent browsing on anciliary folders, essentially, anything other than css, js, fonts and images.

12.  We've included a lot of the original code that came with the template, but is no longer being used, such as the js, scss, images and php folders.  A lot of the css is unnecessary too.  It remains here if you want it.

13.  If the pagination arrows do not appear on the list pages, then you may need to install a thorough unicode font such as Symbola, or change the characters for the left and right arrows to something else.