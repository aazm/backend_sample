# config valid for current version and patch releases of Capistrano
lock "~> 3.11.0"

set :application, "turing"
set :scm, :git

set :user, "deploy"
set :group, "www"


set :scm_password, "none"
set :scm_username, "git"
set :git_shallow_clone, 1
set :repo_url, "git@github.com:aazm/turing_backend_challenge.git"

# Default branch is :master
# ask :branch, `git rev-parse --abbrev-ref HEAD`.chomp

set  :keep_releases, 10
set  :use_sudo, false

set :linked_dirs, ["vendor", "storage/logs", "storage/images", "storage/app/public"]
set :writable_dirs, ["bootstrap", "storage", "storage/logs", "storage/logs", "storage/framework", "storage/cache", "storage/sessions", "storage/views", "public/images"]

set :webserver_user, "www-data"
set :use_set_permissions, true
set :permission_method, :acl

set :use_composer, true
set :composer_bin, "composer"
set :update_vendors, false
set :composer_options, "--verbose --prefer-dist --optimize-autoloader"

namespace :deploy do

    desc "Build"
    after :updated, :build do
        on roles(:app) do
            within release_path  do
                execute :composer, "install --no-dev --quiet" # install dependencies
                execute :chmod, "u+x artisan" # make artisan executable
            end
        end
    end

    desc "Restart"
    task :init do
        on roles(:app) do
            within release_path do
                execute :mkdir, "storage/app"
                execute :mkdir, "storage/logs"
                execute :mkdir, "storage/framework"
            end
        end
    end

    task :restart do
        on roles(:app) do
            within release_path  do
                execute :chmod, "-R 777 storage"
            end
        end
    end

    after :updated, "composer:install"
    after :updated, "laravel:upload_config"
    after :updated, "laravel:copy_config"
    after :updated, "laravel:update_js_version"
    after :published, "laravel:optimize"
    after :published, "laravel:migrate"

    after :published, "laravel:symlink"
    after :published, "laravel:reset_chmod"
    after :published, "laravel:cp_missing"
    after :published, "laravel:up"

end

namespace :composer do
    desc "Running Composer Install"
    task :install do
        on roles(:composer) do
            within release_path do
                execute :composer, "install --no-dev --quiet --prefer-dist --optimize-autoloader"
            end
        end
    end
end

namespace :laravel do
    task :fix_permission do
        on roles(:laravel) do
            execute :chmod, "-R ug+rwx #{shared_path}/storage/ #{release_path}/bootstrap/cache/"
            execute :chgrp, "-R www-data #{shared_path}/storage/ #{release_path}/bootstrap/cache/"
        end
    end
    task :configure_dot_env do
    dotenv_file = fetch(:laravel_dotenv_file)
        on roles (:laravel) do
        execute :cp, "#{dotenv_file} #{release_path}/.env"
        end
    end

    desc "Run Laravel Artisan migrate task."
    task :migrate do
        on roles(:app), in: :sequence, wait: 5 do
            within release_path  do
                execute :php, "artisan migrate"
            end
        end
    end

    desc "Run Laravel Artisan seed task."
    task :seed do
        on roles(:app), in: :sequence, wait: 5 do
            within release_path  do
                execute :php, "artisan db:seed"
            end
        end
    end

    desc "Optimize Laravel Class Loader"
    task :optimize do
        on roles(:app), in: :sequence, wait: 5 do
            within release_path  do
                execute :php, "artisan clear-compiled"
            end
        end
    end

    desc "Copy missing image"
    task :cp_missing do
        on roles(:app), in: :sequence, wait: 5 do
            within release_path  do
                execute :cp, "#{release_path}/resources/assets/img/missing.png #{release_path}/public/images"
                execute :cp, "#{release_path}/resources/assets/img/header.png #{release_path}/public/images"
            end
        end
    end

   desc "Laravel down"
    task :down do
        on roles(:app), in: :sequence, wait: 5 do
            within release_path do
                execute :php, "artisan down"
                execute :sudo, "/etc/init.d/php7.2-fpm restart"
            end
        end
    end

   desc "Laravel up"
    task :up do
        on roles(:app), in: :sequence, wait: 5 do
            within release_path do
                execute :php, "artisan up"
                execute :sudo, "/etc/init.d/php7.2-fpm restart"
            end
        end
    end

    desc "Reset chmod"
    task :reset_chmod do
        on roles(:app), in: :sequence, wait: 5 do
            within release_path do
                execute :sudo, "chmod g+rwX -R /var/www"
                execute :sudo, "chown ubuntu:www -R /var/www"
             end
        end
    end

    desc "Symlink app storage"
    task :symlink do
        on roles(:app), in: :sequence, wait: 5 do
            within release_path do
                execute :php, "artisan storage:link"
                execute :ln, "-s ./assets/img ./public/img"
                execute :ln, "-s ../storage/images ./public/files"
            end
        end
    end

    desc "TAR assets"
    task :tar_assets do
        sh 'gtar czf ./assets.tar.gz ./public/assets/*'
    end

    desc "upload assets"
    task :upload_tarball do
        on roles(:app), in: :sequence, wait: 1 do
            upload! './assets.tar.gz', "/home/ubuntu/"
        end
    end

    desc "duplicate assets"
    task :duplicate_assets do
        on roles(:app), in: :sequence, wait: 1 do
            within release_path do

                execute :cp, "./public/assets/api/*json", "./public/"
                execute :cp, "./public/assets/*jpg", "./public/"
                execute :cp, "./public/assets/*jpeg", "./public/"
                execute :cp, "./public/assets/*svg", "./public/"
                execute :cp, "./public/assets/*svg", "./public/"
                execute :cp, "-R ./public/assets/fonts", "./public/"

            end
        end
    end

    desc "Update js version"
    task :update_js_version do
        on roles(:app), in: :sequence, wait: 5 do
            within release_path do
                execute :php, "artisan viza:js_version"
            end
        end
    end
end
