namespace :deploy do
  task :set_app_config do
    app_config.map do |c|
      run "rm -f #{latest_release}/#{c} && ln -s #{shared_path}/config/#{c} #{latest_release}/#{c}"
    end
  end
end

after "deploy:finalize_update", "deploy:set_app_config"
