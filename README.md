# Wubtitle

Wubtitle is a plugin that generates:
-   Subtitles and transcript of uploaded videos in media library
-   Transcripts of youtube video

## Functionality

-   Automatic subtitle generation
-   Ability to enable or disable generated subtitle
-   Generates video transcriptions from Wordpress Media library and from YouTube
-   Support both Gutenberg and classic editor
-   Editing and management of all transcriptions

## Requirements

-   npm
-   composer

## Development configuration

-   Clone the repository:
    -   via https: `git clone https://github.com/CTMobi/Wubtitle-Wordpress-Plugin.git`
    -   via ssh: `git clone git@github.com:CTMobi/Wubtitle-Wordpress-Plugin.git`

-   Install composer and npm, then build
    -   `composer install`
    -   `npm install`
    -   `npm run build`

## Developer code quality commands

-   `composer phpcs`

-   `composer phpmd`

-   `composer check-php`

-   `composer fixphp`

-   `npm run fixjs`
