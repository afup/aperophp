# Add a recipe for Composer

namespace :composer do
  desc "Runs composer to install vendors from composer.lock file"
  task :install, :roles => :app, :except => { :no_release => true } do
    php_bin = fetch :php_bin, "/usr/bin/php"
    composer_bin = fetch :composer_bin, "#{php_bin} composer.phar"
    composer_options = fetch :composer_options, "COMPOSER_PROCESS_TIMEOUT=4000"

    run "#{try_sudo} sh -c 'cd #{latest_release} && #{composer_options} #{composer_bin} install'"
  end

  task :copy_vendors, :except => { :no_release => true } do
    composer_vendor = fetch :composer_vendor, 'vendor'
    run "vendorDir=#{current_path}/#{composer_vendor}; if [ -d $vendorDir ] || [ -h $vendorDir ]; then cp -a $vendorDir/* #{latest_release}/#{composer_vendor}; fi;"
  end
end

before "composer:install", "composer:copy_vendors"
after "deploy:finalize_update", "composer:install"
