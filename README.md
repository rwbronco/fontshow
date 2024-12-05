# FontShow
Docker container for displaying fonts in a folder

![FontShow](https://github.com/rwbronco/fontshow/blob/main/fontshow.png)

There isn't a simple docker container that I can find that will act as a sort of font-repository where I can browse and preview fonts and then download them. So I decided to just make one. This docker container uses php:apache, composer, and [php-font-lib](https://github.com/dompdf/php-font-lib) to pull a list of fonts from a directory, read the metadata, cache the info, and display their names and a preview on a webpage where users can download them. This project is in very early stages, so be sure to report any issues or bugs you find, and be patient because I'm a graphic designer, not a programmer!

# INSTALLATION
## Docker Compose
Use git clone to copy the repository to your computer. On Windows, open a command prompt at the location you want and run:
```
git clone https://github.com/rwbronco/fontshow.git
```
In that same folder build and launch the container
```
docker-compose up --build
```
Check http://localhost:8090 to ensure the container has launched correctly. To launch it in the future without rebuilding it:
```
docker-compose up -d
```

# CONFIGURATION

## Port Number
To change the port number that fontshow uses, simply change the port in the [docker-compose.yml](https://github.com/rwbronco/fontshow/blob/main/docker-compose.yml) file:
```
    ports:
      - 8090:80
```

## Front End
The front end is currently designed around a 3 column layout, but I may try to build some configuration into that. If you know CSS you can modify the _.font-display_ and _.font-list_ classes at the top of [index.html](https://github.com/rwbronco/fontshow/blob/main/php/www/index.html).

## Cache
The cache is set to refresh every day, but can be triggered by a button on the front end next to the text preview box. To change the duration between refreshes, edit this line in [get-fonts.php](https://github.com/rwbronco/fontshow/blob/main/php/www/get-fonts.php):
```
$cacheTime = 86400; // Cache for 1 day
```
To remove the button, remove this line of code from [index.html](https://github.com/rwbronco/fontshow/blob/main/php/www/index.html). To hide the button simply comment it out to disable it:
```
<button id="refresh-cache-btn">Refresh Cache</button>
```

## Adding Fonts
To add fonts, simply drop the font files into the /fonts/ directory, open your browser to FontShow, and click the "Refresh Cache" button.

# TROUBLESHOOTING

## Fonts don't load
Give it some time on it's first launch to scan the /fonts/ folder. This goes for adding new fonts and them showing up in fontshow. In the background [get-fonts.php](https://github.com/rwbronco/fontshow/blob/main/php/www/get-fonts.php) is pulling the metadata from each of the fonts and creating a [font-cache.json](https://github.com/rwbronco/fontshow/blob/main/php/www/font-cache.json) array for the front end to pull from. This can be pretty slow depending on the amount of fonts you have - and hence, the reason for the cache.

## Notes
I haven't tried this on every single font type yet, so I'm not sure what's going to work (variable fonts, etc) and what's not at this point. Bugs will be worked out as they're discovered. Please leave an issue if you run into any problems!
