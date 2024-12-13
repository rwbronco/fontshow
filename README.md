# FontShow
Docker container for displaying fonts in a folder

![FontShow](https://github.com/rwbronco/fontshow/blob/main/fontshow.png)

There isn't a simple docker container that I can find that will act as a sort of font-repository where I can browse and preview fonts and then download them. So I decided to just make one. This docker container uses php:apache, composer, and [php-font-lib](https://github.com/dompdf/php-font-lib) to pull a list of fonts from a directory, read the metadata, cache the info, and display their names and a preview on a webpage where users can download them. This project is in very early stages, so be sure to report any issues or bugs you find, and be patient because I'm a graphic designer, not a programmer!

# INSTALLATION

## Build & Launch with Docker Compose
Use git clone to copy the repository to your computer or click the green "Code" button at the top of this page and download and extract the zip file to wherever you'd like. To build and launch FontShow, open a command prompt (or however you run these commands on your OS) in the same folder as the [docker-compose.yml](https://github.com/rwbronco/fontshow/blob/main/docker-compose.yml) and run:
```
docker-compose up --build
```
This will take a moment as it builds the container and launches it. Check http://localhost:8090 to ensure the container has launched correctly. To launch it in the future without rebuilding it:
```
docker-compose up -d
```

## Compose File
### Port Number
To change the port number that FontShow uses, simply change the port from 8090 to some other port number in the [docker-compose.yml](https://github.com/rwbronco/fontshow/blob/main/docker-compose.yml) file:
```
    ports:
      - 8090:80
```

### Database Credentials
The [docker-compose.yml](https://github.com/rwbronco/fontshow/blob/main/docker-compose.yml) file sets "user" and "password" as the default credentials for the database. ***Please change these for security reasons.*** Once you've changed them, please make sure you also change them in [php/www/config.php](https://github.com/rwbronco/fontshow/blob/main/php/www/config.php). The root password isn't used anywhere by FontShow, but should also be changed for security reasons.
```
      environment:
          MYSQL_ROOT_PASSWORD: CHANGEME
          MYSQL_DATABASE: fontshowDB
          MYSQL_USER: user
          MYSQL_PASSWORD: password
```

# SITE CONFIGURATION

## Config File
Almost everything from the site color to footer contents can be changed in the [php/www/config.php](https://github.com/rwbronco/fontshow/blob/main/php/www/config.php).

## Database Connection
[php/www/config.php](https://github.com/rwbronco/fontshow/blob/main/php/www/config.php) will have the default database credentials. If you changed them in the compose file earlier (***You should have!***) then you'll need to change them in the config file!

## Cache
The cache is set to refresh every day, but can be triggered by a button on the front end next to the text preview box. This duration length hasn't been made configurable as it's not something most people will need to change since there's a refresh button to immediately refresh it. To change the duration between refreshes, edit this line in [php/www/get-fonts.php](https://github.com/rwbronco/fontshow/blob/main/php/www/get-fonts.php):
```
$cacheTime = 86400; // Cache for 1 day
```

## Adding Fonts
To add fonts, simply drop the font files into the /fonts/ directory, open your browser to FontShow, and click the "Refresh Cache" button. FontShow now supports subfolders within the /fonts/ directory. The front end of the site where fonts are displayed makes no distinction. This allows you to more neatly organize your /fonts/ director, and to make use of fonts that may already be nested in folders.

# TROUBLESHOOTING

## Fonts don't load
Give it some time on it's first launch to scan the fonts in the /fonts/ folder. This goes for adding new fonts and them showing up in FontShow after pressing the Refresh Cache button. In the background [php/www/get-fonts.php](https://github.com/rwbronco/fontshow/blob/main/php/www/get-fonts.php) is pulling the metadata from each of the fonts and adding it to the database for [php/www/index.php](https://github.com/rwbronco/fontshow/blob/main/php/www/index.php) to pull from. This can be pretty slow depending on the amount of fonts you have. The button text will update letting you know that fetching is being done in the background.

## Variable Fonts
Variable fonts are unsupported by the php-font-lib library I'm using, so they won't be supported by FontShow unless they're added to php-font-lib or someone recommends another library I can use that does support them. There may be some other font types that don't work, but the main ones like OTF and TTF do. Bugs will be worked out as they're discovered. Please leave an issue if you run into any problems!

## Current bugs
- Footer doesn't stick to the bottom of the site and leaves a gap when on mobile and the aspect ratio changes to landscape.

# ROADMAP

## Planned Features
- Site logo and favicon (configurable)
- Index of and merging analogous subfamily tags - ex: gras = bold, italique = italic, normal = regular, etc.
- Search function: I'd love to have a search function that filters the fonts based on a user input
- Single-column for mobile: Currently the site keeps the same number of columns no matter the reported browser width. For vertical screens I'd like it to automatically fall back to single-column (configurable) so users can read the previews.

## Long-term Goals
- Users: Allow user creation and roles
- Web-based font management: It's currently just displaying fonts from a folder. I'd like to allow a user to manage the fonts in that folder and potentially even modify a font's metadata. Hiding, renaming, uploading, deleting, etc.
- Expanded Font Preview: Similar to the way DaFont and others handle this. Instead of simply downloading, clicking the font name would open a subpage for that font with expanded previews for each font character, user-added tags, etc.
- Figure out another solution to the "Refresh Cache" button without asking for constant folder scans
