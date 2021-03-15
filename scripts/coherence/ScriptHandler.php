<?php

namespace DrupalProject\Coherence;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class ScriptHandler {

  public static function createRequiredFiles() {

  }

  public static function removeGitDirectories()
  {
    // Get rid of any .git directories that Composer may have added.
    // n.b. Ideally, there are none of these, as removing them may
    // impair Composer's ability to update them later. However, leaving
    // them in place means managing submodules, which is worse.
    $dirsToDelete = [];
    $finder = new Finder();
    foreach (
      $finder
        ->directories()
        ->in(getcwd())
        ->ignoreDotFiles(false)
        ->ignoreVCS(false)
        ->depth('> 0')
        ->name('.git')
      as $dir) {
      $dirsToDelete[] = $dir;
    }
    $fs = new Filesystem();
    $fs->remove($dirsToDelete);
  }

  public static function removeSensitiveFiles() {
    // Get rid of CHANGELOG.txt and INSTALL.txt from Drupal core.
    $files = [
      './docroot/core/CHANGELOG.txt',
      './docroot/core/INSTALL.txt',
      './docroot/core/INSTALL.sqlite.txt',
      './docroot/core/INSTALL.mysql.txt',
      './docroot/core/INSTALL.pgsql.txt',
      './docroot/core/MAINTAINERS.txt',
    ];
    $fs = new Filesystem();
    $fs->remove($files);
  }

}
