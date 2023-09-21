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
    cd('{{release_path}}');

    run('npm ci && npm run build');
});

desc('Composer dump autoload');
task('composer:dump:autoload', function () {
    run('cd {{release_path}} && composer dump-autoload');
});

desc('Creates the symbolic links configured for the application');
task('artisan:storage:link', artisan('storage:link --relative', ['min' => 5.3]));

// Hooks
before('artisan:migrate', 'artisan:cache:clear');
before('artisan:migrate', 'composer:dump:autoload');
after('deploy:vendors', 'build');
// after('deploy:symlink', 'artisan:queue:restart');
after('deploy:failed', 'deploy:unlock');
