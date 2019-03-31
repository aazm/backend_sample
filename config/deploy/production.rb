set :deploy_to, "/var/www/turing"
set :branch, "master"

role :app, %w{185.205.210.3}, user: 'deploy', primary: true
set :ssh_options, {
    forward_agent: true,
    auth_methods: %w[publickey],
    keys: %w[~/.ssh/id_rsa]
}

namespace :laravel do

  desc 'Copy non-git ENV specific files to servers.'
  task :upload_config do
    on roles(:app), in: :sequence, wait: 1 do
      upload! './config/deploy/envs/mcs.env', "#{deploy_to}/shared/.env"
    end
  end

 desc 'Get stuff ready prior to symlinking'
 task :copy_config do
    on roles(:app), in: :sequence, wait: 1 do
        execute "cp #{deploy_to}/shared/.env #{release_path}/.env"
    end
 end

    desc "untar assets"
    task :untar_assets do
        on roles(:app), in: :sequence, wait: 1 do
            execute :tar, "-xzf  /home/ubuntu/assets.tar.gz -C /var/www/production/current/"
        end
    end


desc 'maintenance mode for production'
 task :maintenance do
        on roles(:app), in: :sequence, wait: 5 do
            within release_path  do
                execute :php, "artisan down"
            end
        end
 end
end

namespace :deploy do
        after :published, "laravel:tar_assets"
        after :published, "laravel:upload_tarball"
        after :published, "laravel:untar_assets"
        after :published, "laravel:duplicate_assets"
end