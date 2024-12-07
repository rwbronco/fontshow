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
Variable fonts are unsupported by the php-font-lib library I'm using, so they won't be supported by FontShow unless they're added to php-font-lib or someone recommends another library I can use that does support them. There may be some other font types that don't work, but the main ones like OTF and TTF do. Bugs will be worked out as they're discovered. Please leave an issue if you run into any problems!

# ROADMAP

## Planned Features
- Site logo and favicon (configurable)
- Filterable by font weight/style: Simple things like Font Subfamily (ex: Bold) and Font Weight (ex: 700) will be easy to have in a dropdown filter. I would love to allow users to add tags to fonts that are then added to the filterable categories (ex: script, distressed, vintage, etc)
- Search function: I'd love to have a search function that filters the fonts based on a user input
- Single-column for mobile: Currently the site keeps the same number of columns no matter the reported browser width. For vertical screens I'd like it to automatically fall back to single-column (configurable) so users can read the previews.

## Long-term Goals
- Users: Allow user creation and roles
- Web-based font management: It's currently just displaying fonts from a folder. I'd like to allow a user to manage the fonts in that folder and potentially even modify a font's metadata. Hiding, renaming, uploading, deleting, etc.
- Expanded Font Preview: Similar to the way DaFont and others handle this. Instead of simply downloading, clicking the font name would open a subpage for that font with expanded previews for each font character, etc.
- Figure out another solution to the "Refresh Cache" button without asking for constant folder scans
