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
│   ├── config.php
│   └── functinos.php
├── js
└── template
```

- `assets` keeps all the static content such as fonts, images and stylesheets. PDFs or bibtex file would be added here.
- `content` mirrors the routing structure of the webpage. Each file/folder can be navigated to. New pages would be added here.
- `includes` contains the basic configuration of the application and necessary php functions.
    - `config.php`: the navigation and global variables are defined here. If you would like to add a new page to the website, you can add the name of the file and the title of the page here. For example, if you chose to host the website not at the root level (e.g. example.com/yago-project), you can update the `site_url` to `/yago-project`.
    - `functions.php`: functions that are called within the application are store here. For example, the navigation menu is generated via `nav_menu()`. Those functions can be called in any .php file. For example, you can define a constant there and get its value via a function.
- `js` includes all the javascript files.
- `template` contains the basic html page layout with the header and footer. If you like to add meta tags, other stylesheet or libraries that is the place.

## Deploy

When deploying this application make sure to also copy the hidden `.htaccess` file to ensure proper routing.
If the website is not hosted at root (e.g. example.com/yago-project/...) adjust the `site_url` in `includes/config.php`.


## License

Copyright 2020 Yago Project

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
