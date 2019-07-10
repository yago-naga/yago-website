# YAGO Project Website

## Set Up

Point your php server to the root directory of this project.

- [mamp](https://www.mamp.info/de/) for mac
- [xampp](https://www.apachefriends.org/de/index.html) for windows, linux or mac

## Folder
```
.
├── assets
│   ├── fonts
│   └── images
├── content
│   └── downloads
├── includes
├── js
└── template
```

- `assets` keeps all the static content such as fonts, images and stylesheets. PDFs or bibtex file would be added here.
- `content` mirrors the routing structure of the webpage. Each file/folder can be navigated to. New pages would be added here.
- `includes` contains the basic configuration of the application and necessary php functions.
- `js` includes all the javascript files.
- `template` contains the basic html page layout with the header and footer. If you like to add meta tags, other stylesheet or libraries that is the place.

## Deploy

When deploying this application make sure to also copy the hidden `.htaccess` file to ensure proper routing.
If the website is not hosted at root (e.g. example.com/yago-project/...) adjust the `site_url` in `includes/config.php`.
