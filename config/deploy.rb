# encoding: utf-8

load "config/recipes/railsless"
load "config/recipes/composer"
load "config/recipes/database"
load "config/recipes/railsless"

set :app_config, %w(app/config.php)
load "config/recipes/app_config"

set :shared_children, ["app/log"]

set :scm,           :git
set :repository,    "git@github.com:afup/aperophp.git"
set :deploy_via,    :remote_cache
set :branch,        "master"

set :keep_releases, 5
set :use_sudo,      false
set :deploy_to,     "TODO"

server 'TODO', :app, :web, :db, :primary => true

set :user, "TODO"

after "deploy:restart", "deploy:cleanup"

#TODO backup database
