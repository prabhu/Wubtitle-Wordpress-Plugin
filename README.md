[![Codacy Badge](https://app.codacy.com/project/badge/Grade/fe8dec042ddf47a5a00fb1b7fbf814dd)](https://www.codacy.com?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=CTMobi/Wubtitle-Wordpress-Plugin&amp;utm_campaign=Badge_Grade)   [![Codacy Badge](https://app.codacy.com/project/badge/Coverage/fe8dec042ddf47a5a00fb1b7fbf814dd)](https://www.codacy.com?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=CTMobi/Wubtitle-Wordpress-Plugin&amp;utm_campaign=Badge_Coverage)

# WP-Ear2Words
Ear2Words è un plugin per la generazione automatica dei sottotitoli dei video caricati sui media e della trascrizione dei video caricati e dei video youtube.


## Funzionalità

- Generazione dei sottotitoli
- Attivazione\Disattivazione dei sottotitoli
- Generazione transcrizione dei video ( video nella media e video youtube )
- Inserimento trascrizione nell'articolo tramite blocco gutenberg o finestra modale da classic editor
- Possibilità di utilizzare lo shortcode per inserire la trascrizione nell'articolo
- Modifica e gestione di tutte le trascrizioni

## Requisiti

* npm
* composer

## Configurazione sviluppo

* Fai il clone del repository:
    * con https: `git clone https://gitlab.com/ear2words/wp-ear2words.git`
    * con ssh: `git clone git@gitlab.com:ear2words/wp-ear2words.git`
* Installa composer e npm e eseguil il build
    * `composer install`
    * `npm install`
    * `npm run build`


## Altri comandi pipeline

* `composer phpcs`

* `composer phpmd`

* `composer check-php`

* `composer fixphp`

* `npm run fixjs`
