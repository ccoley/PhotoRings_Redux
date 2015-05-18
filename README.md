Welcome to PhotoRings!
======================

PhotoRings is a social photo sharing website that aims to make it easy to share your photos with friends and family, no matter how complex your specific needs are.

**As of January 2014 PhotoRings is defunct and is no longer hosted anywhere.**

Also, we were fairly inexperienced when we wrote this. There are definitely security vulnerabilities present. *Do not use this code as a guide.* Its only purpose now is to be remembered by the authors.


#### Configuration
After cloning the project, your first step should be setting up a config file. The application will not function without one.

CD into the application's root directory, then copy the example config file to a file named `config.ini` like so:

```bash
cd your/photorings/root/directory
cp example.config.ini config.ini
```

Then open `config.ini` in your editor and change all instances of `!!ChangeMe` to the appropriate value. If you want to change the other config values you can, but reasonable defaults have been assigned already.

You can check the validity of your configuration by navigating to `http://{your-photorings-domain}/admin/ViewConfig.php` in your browser.
