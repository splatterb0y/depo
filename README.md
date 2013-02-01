
Depo is a PHP-based wrapper for the repo tool that is used to checkout sources from different git servers and repositories.
This script adds features that are geared towards usage with Drupal. 

* Features
  * Applying of patch files to a certian repository after checkout
  * Downloading a file into a repository directory 
  * Automatically adding version numbers to the *.info file to make it possible to use l10n_update and drupal update
  * Check for newer versions on checking out the repository from drupal.org 

* Requirements
  * git and repo tool in $PATH
  * php-cli installed (PHP-Version >5.3 | Linux / Os X tested)
