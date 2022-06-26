# DAPNetNotifierPHP

By LeRoy Miller, KD8BXP June 25, 2022 v0.1.0 init release  
 This script is loosely based on DAPNETNotifier by N8ACL which in turn was based on
 DAPNET2APRS script by KR0SIV.  
 https://github.com/KR0SIV/DAPNET2APRS  
 https://github.com/n8acl/DAPNETNotifier/  
 Both of which are python based, and honestly python just confuses the heck out of me, 
 needless to say I couldn't get either one to work reliably. N8ACL's worked once, but not with my pager id.   
Not being good with python, just barely enough to get by. I fell back to using PHP on the cli. Yes, it's weird, it takes a little setup, but it works.  
This script will scrape your hot spot, format the information into arrays (much nicer to work with this way), attempt to decide if a new message for you has arrived, and forward that message to pushover. (Other services could be added as needed.)  
A simple web page was found when looking at the html source for the dashboard.  
/mmdvmhost/pages.php It's not perfect, but should be better then having the hotspot render the full dashboard everytime you make a request.  
It is recommended to only update about every 60 seconds or so, but can be changed as needed.  
This script goes into a loop, a & on the command line should let it run in the background. Error message reporting is turned off, if you are having issues you can turn it back on, and see if you can figure out what is going on.   


I have PHP 7.4 installed, php74-cli, php7.4-command, php7.4-curl, php7.4-json on my test machine. (I beleive these are all required for this too work, thou I installed most of them a while ago)  
simple_html_dom is required http://simplehtmldom.sourceforge.net/ for more information.  

Posiable limitations - assume that you don't get many pages, so the 1st key should always be the newest on the dashboard. At this point if you get more then 1 page in the same time period, pages could be lost.  

This software is provided free of charge, and as-is where is. It may or may not work for you.   

## Installation

install PHP-CLI, PHP-curl and PHP-json (I didn't use it for this project, but it's nice to have).  
If it's not in your repositories, or you are using Windows see https://www.php.net/  

This was tested on a Linux Mint 20.3 machine with php7.4 installed, and is currently running for me on a Raspberry PI with PHP7.0 installed.  I beleive it will work with PHP 5 and newer without too many problems.  

You may want to have this auto start on system reboot, I use crontab, but you may have a better way to do it.  

## Contributing

Please if you make changes/improvements let me know. Enjoy.  

1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request

## Support Me

If you find this or any of my projects useful or enjoyable please support me.  
Anything I do get goes to buy more parts and make more/better projects.  
https://www.patreon.com/kd8bxp  
https://ko-fi.com/lfmiller  
https://www.paypal.me/KD8BXP  

## Other Projects

https://www.youtube.com/channel/UCP6Vh4hfyJF288MTaRAF36w  
https://kd8bxp.blogspot.com/  


## Credits

Copyright (c) 2022 LeRoy Miller

## License

This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses>
