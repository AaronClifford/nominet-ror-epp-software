# Free Nominet Right Of Registration EPP Software - Early Create Contact & Domain

**I am aware of a looping issue with the EPP, I belive this is to do with a delay in time of nominet processing the addition of the IP address to the test bed, I'm currently testing this to try and resolve the issue. For now I've added error catching to this. **

The full release will be some time over the weekend, in the mean time I have released the script to create a contact a domain on the EPP testbed.
I have successfully tested the contact create and domain create. As I'm keen to get this released to people as fast as I can so you can get the requirements met
there isn't full error checking in place yet, but there will be for the full release in the coming days.

Any questions open an issue here and I'll be happy to help.

# Installation 

This installation presumes you are running on a linux server with php installed, I'd suggest spinning up a Droplet at DigitalOcean (London based, size not really important) https://m.do.co/c/c195ffdba437 (Referal Link). 

The only files you really need to access are the config/Config.php and the logs folder, everything else you should leave as is for the time being.

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

You now need to open and edit the config file (config/Config.php), either via FTP if you have access or via command line using VIM or VI (will use vi in this example),
You need to edit the following settings to create a test contact and domain:

```
    'epp' => [
        'tag' => 'YOURTAG', // Enter your live tag here
        'password' => 'EPPTESTBEDPASSWORD', // Enter the testbed EPP password you set
        'testRegistrantID' => 'TEST-REG-1', // Enter a made up registrant ID here, change this every time you run the script for multiple tests
        'testRegistrantName' => 'Your Name', // If you are on a self managed tag this must match the registrant account on your live account
        'testRegistrantOrg' => 'Your Organisation', // If you are on a self managed tag this must match the registrant account on your live account
        'testDomain' => 'test-doamain-aahere.uk', // This must be a domain that is on the RoR list to correctly complete the steps from nominet.
    ]
```

Once you have edited the config you can now save the file, and from the command line run *php index.php*, this will now run the script, create the
contact and create the test domain. All XML responses are stored in the logs folder so if you have any issues you'll be able to find them there. If
everything goes to plan the following should output on the screen:

```
MESSAGE: EPP Connected
MESSAGE: Contact Created Successfully
MESSAGE: Domain testdomainhere.uk Created Successfully
```

Please create any issues on this repository if you find any problems, I apologies for the rushed nature of this but I understand time is of the 
essence. I have ran this successfully and have spoken to Nominet who have confirmed that I have completed the requirements to be able to take part in
the RoR release, but I'd recommend contacting them via live chat in the online services to confirm. 

When you run the script for the first time, it will create a REGISTRANT file in the root directory, if you wish to run the script again delete this 
file and change the acccount ID in the config to create another test domain.
