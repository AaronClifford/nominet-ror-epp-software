# Free Nominet Right Of Registration EPP Software - RoR Script

Any questions open an issue here and I'll be happy to help.

This script also uses the testbed dac, when running in production you will be required to set the live DAC and RoR epp settings as follows:

DAC: dac.nic.uk port 3043
RoR EPP: ror-epp.nominet.org.uk port 700

# Installation 

This installation presumes you are running on a linux server with php installed, I'd suggest spinning up a Droplet at DigitalOcean (London based, size not really important) https://m.do.co/c/c195ffdba437 (Referal Link). 

The only files you really need to access are the config/Config.php, the domains  and the logs folder, everything else you should leave as is for the time being.

You will need to add an IP address and password to the web domain manager in your Nominet account online (and add them to the config).

On your server, preferably the one you use for drop catching currently, perform the following command (somewhere on your server you can remember):

If you don't have git installed please follow: https://git-scm.com/book/en/v2/Getting-Started-Installing-Git, or simply download the zip files and upload them to your server.

```
git clone git@github.com:AaronClifford/nominet-ror-epp-software.git
```

Once this has cloned move into the directory:

```
cd nominet-ror-epp-software
```

You now need to open and edit the config file (config/Config.php), either via FTP if you have access or via command line using VIM or VI,
You need to edit the following settings to create a test contact and domain:

```
    'epp' => [
        'tag' => 'YOURTAG', // Enter your live tag here
        'password' => 'EPPTESTBEDPASSWORD', // Enter the testbed EPP password you set
        'liveRegistrantID' => 'LIVEREGID', // enter your live registration ID, or for testing the one you created with the test create.
    ]
```

Once you have edited the config you can now save the file, and from the command line run: 

```
php index.php
```

This will now run the script, ideally you want to run this script at 13:59:00, the script will have time do everything it needs then wait for the 2PM before
sending requests.

** The final update for the script will be it slightly reconfigured for running on a cron **

This script is only setup for sending 6 requests, but can be modified quite easily to send 9. If I have time before the release date I may add some config for this.

Please create any issues on this repository if you find any problems, I apologies for the rushed nature of this but I understand time is of the 
essence.