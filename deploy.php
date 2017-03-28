<?php
/*
 * This file has been generated automatically.
 * Please change the configuration for correct use deploy.
 */

require 'recipe/symfony3.php';

// Configure servers
server('prod', '51.254.124.178', 22)
    ->user('mesmenus')
    ->password('xxx')
    ->stage('prod')
    ->env('deploy_path', '/home/mesmenus/web/prod')
    ->env('branch', 'master')
    ->env('env', 'prod')
;


// Set configurations
set('repository', 'git@bitbucket.org:JonathanBaudoin/mesmenus.git');
set('keep_releases', 5);
set('shared_files', ['app/config/parameters.yml']);
set('shared_dirs', [
    'var/logs',
    'vendor',
    'web/bundles',
]);
set('writable_dirs', [
    'var/cache',
    'var/logs',
    'var/sessions',
    'web/assets',
    'web/bundles',
    'web/css',
    'web/js',
]);

set('writable_use_sudo', false);

env('composer_options', 'update --no-dev --verbose --prefer-dist --optimize-autoloader --no-progress --no-interaction');

task('vendors', function () {
    cd('{{release_path}}');
    run('npm install');
    run('bower update');
    run('php bin/console assets:install --symlink --env=prod');
    run('php bin/console assetic:dump --env=prod');
    run('php bin/console fos:js-routing:dump --env=prod');
});

task('database', function () {
    cd('{{release_path}}');
    run('php bin/console doctrine:schema:update --force --env=prod');
});

task('cache', function () {
    cd('{{release_path}}');
});

after('deploy:vendors', 'vendors');
after('cleanup', 'deploy:writable');
after('deploy:cache:warmup', 'cache');
before('deploy:symlink', 'database');