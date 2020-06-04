# Wubtitle

Wubtitle is a plugin that generates:
-  subtitles and transcript of uploaded videos in media library
-  transcript of youtube video

## Functionality

- Subtitle generation
- Subtitle ActivaRequirementstion \ Deactivation
- Generation of video transcription (average video and youtube video)
- Transcript insertion in the article through gutenberg block or modal window from classic editor
- Editing and management of all transcriptions

## Requirements

* npm
* composer

## Development configuration

* Clone the repository:
    * via https: `git clone https://gitlab.com/ear2words/wp-ear2words.git`
    * via ssh: `git clone git@gitlab.com:ear2words/wp-ear2words.git`
* Install composer and npm, then build
    * `composer install`
    * `npm install`
    * `npm run build`

## Other pipeline commands

* `composer phpcs`

* `composer phpmd`

* `composer check-php`

* `composer fixphp`

* `npm run fixjs`
