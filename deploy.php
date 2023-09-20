<?php
namespace Deployer;

require 'recipe/laravel.php';
require 'contrib/npm.php';

set('bin/php', 'php8.2');
set('bin/composer', '{{bin/php}} $(which composer)');

set('writable_mode', 'chmod');

// Project name
set('application', 'mkg-ornament');

// Project repository
set('repository', 'git@github.com:igor-kamil/mkg-ornament.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', ['storage', 'resources/fonts']);

// Writable dirs by web server
add('writable_dirs', []);
set('allow_anonymous_stats', false);

// Hosts

host('igo')
    ->set('hostname', 'igo')
    ->set('deploy_path', '/home/igo.sk/private/mkg-ornament');

// Tasks

task('build', function () {
    run('cd {{release_path}} && {{bin/npm}} run build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

after('deploy:update_code', 'npm:install');
after('deploy:shared', 'build');
