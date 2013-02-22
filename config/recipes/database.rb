namespace :database do
  namespace :mysql do
    desc <<-DESC
      Create a compressed backup of given mysql database.
      This task must not be called directly because it need :db_config variable who contains 
      database connection informations 
    DESC
    task :backup, :roles => :app, :only => { :primary => true } do 
      database_dump = fetch :database_dump, "mysqldump"
      gzip = fetch :gzip, "gzip"
      backup_path = fetch :backup_path, File.join(deploy_to, 'backup')

      # Execute database backup, using release name as archive name to easily association release and dump
      run "#{database_dump} --host=#{db_config['host']} --user=#{db_config['user']} --password #{db_config['dbname']} | #{gzip} > #{backup_path}/#{release_name}.sql.gz" do |ch, stream, out|
        # Password will not be displayed in console or store in history
        ch.send_data "#{db_config['password']}\n" if out =~ /^Enter password:/
      end
    end
  end
  
  desc "Clean databases backups to keep only last backups"
  task :cleanup do
    backup_path = fetch :backup_path, File.join(deploy_to, 'backup')

    # Code duplicate from deploy:cleanup task (and adapted for databases backup)
    count = fetch(:keep_releases, 5).to_i
    local_backup = capture("ls -xt #{backup_path}").split.reverse
    if count >= local_backup.length
      logger.important "no old database backup to clean up"
    else
      logger.info "keeping #{count} of #{local_backup.length} database backup"
      directories = (local_backup - local_backup.last(count)).map { |release|
        File.join(backup_path, release) }.join(" ")

      try_sudo "rm -rf #{directories}"
    end
  end
end

after "deploy:cleanup", "database:cleanup"

