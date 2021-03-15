
# Coherence Code Test

## Installation

*Requires PHP 7.4 and Composer on your local computer*

### Clone repo 

`git clone https://github.com/teamcoherence/code-test.git`

### Install composer dependencies

```
composer install
```

### Setup local server
We’ve provided a [Lando](https://docs.lando.dev/ "Lando") configuration file but you can use the local dev tool of your choice/preference

### Install site

Run Drupal installer

### Sync configs

```
lando drush -y config-set "system.site" uuid "cbca58ed-023f-4fb0-9051-46b392946d8a"
lando drush -y config-import sync
```

### Test files

Test module is located at: `docroot/modules/custom/json_sync_test`
