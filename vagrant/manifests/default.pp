
exec { 'apt-get update':
  command => 'apt-get update > /tmp/updated',
  path    => '/usr/bin/',
  timeout => 60,
  tries   => 3,
  creates => '/tmp/updated',
}

class { 'apt':
  always_apt_update => true,
}

file { '/home/vagrant/.bash_aliases':
  ensure => 'present',
  source => 'puppet:///modules/puphpet/dot/.bash_aliases',
}

file { '/laravel_app_storage':
  owner => 'vagrant',
  group => 'vagrant',
  mode => 'a=rwx',
  ensure => 'directory',
}

exec {'copy over storage dirs':
  command => '/bin/cp -Rp /home/vagrant/laravel/app/storage/* /laravel_app_storage/',
  require => File['/laravel_app_storage'],
  user => 'vagrant',
  creates => '/laravel_app_storage/cache',
  require => Exec['create laravel project'],
}

exec {'chmod storage dirs':
  command => '/bin/chmod -R 777 /laravel_app_storage/',
  require => Exec['copy over storage dirs'],
  user => 'root',
}

package { ['build-essential', 'vim', 'curl', 'htop', 'git-core', 'python-software-properties']:
  ensure  => 'installed',
  require => Exec['apt-get update'],
}

class { 'apache': }

apache::dotconf { 'custom':
  content => 'EnableSendfile Off',
}

apache::module { 'rewrite': }

apache::vhost { 'rss.dev':
  server_name   => 'rss.dev',
  serveraliases => [],
  docroot       => '/rssdb/public',
  port          => '80',
  env_variables => [],
  priority      => '1',
}

apt::ppa { 'ppa:ondrej/php5-experimental':
  before  => Class['php'],
}

class { 'php':
  service => 'apache',
  require => Package['apache'],
}

php::module { 'php5-mysql': }
php::module { 'php5-cli': }
php::module { 'php5-curl': }
php::module { 'php5-intl': }
php::module { 'php5-mcrypt': }

class { 'php::devel':
  require => Class['php'],
}

class { 'php::pear':
  require => Class['php'],
}



class { 'xdebug':
  service => 'apache',
}

xdebug::config { 'cgi':
  remote_autostart => '0',
  remote_port      => '9000',
}
xdebug::config { 'cli':
  remote_autostart => '0',
  remote_port      => '9000',
}

#php::pecl::module { 'xhprof':
#  use_package => false,
#}

#apache::vhost { 'xhprof':
#  server_name => 'xhprof',
#  docroot     => '/var/www/xhprof/xhprof_html',
#  port        => 80,
#  priority    => '1',
#  require     => Php::Pecl::Module['xhprof']
#}

file { '/home/vagrant/laravel':
  ensure => 'directory',
  owner => 'vagrant',
  group => 'vagrant',
  recurse => true,
}

exec { 'create laravel project' :
  command => 'composer create-project laravel/laravel ./',
  cwd => '/home/vagrant/laravel',
  user => 'vagrant',
  path => '/usr/bin',
  creates => '/home/vagrant/laravel/bootstrap',
  timeout => 0,
  require => [ Class['php::composer'], File['/home/vagrant/laravel'] ],
}

exec { 'change laravel project owner' :
  command => 'chown -R vagrant ./ && chown -R vagrant ./',
  cwd => '/home/vagrant/laravel',
  user => 'root',
  path => '/bin',
  require => Exec['create laravel project'],
}

#exec { 'add way generator to app':
#  command => 'sed -i "s:\'providers\' => array(:\'providers\' => array(\'Way\Generators\GeneratorsServiceProvider\'"',
#  user => 'root',
#  path => '/bin',
#  onlyif => '/bin/grep -c "\'providers\' => array(\'Way" /rssdb/app/config/app.php',
#  logoutput => true,
#}

exec { 'add composer dependencies' :
  command => 'composer require --no-update simplepie/simplepie:dev-master way/generators:dev-master phpunit/phpunit:3.7.*',
  cwd => '/home/vagrant/laravel',
  user => 'vagrant',
  path => '/usr/bin',
  require => Exec['create laravel project'],
}

exec { 'symlink app dir':
  command => 'rm -rf /home/vagrant/laravel/app && ln -s /rssdb/app /home/vagrant/laravel/app',
  user => 'root',
  path => '/bin/',
  require => Exec['create laravel project'],
}

exec { 'symlink public dir':
  command => 'rm -rf /home/vagrant/laravel/public && ln -s /rssdb/public /home/vagrant/laravel/public',
  user => 'root',
  path => '/bin/',
  require => Exec['create laravel project'],
}

exec { 'update composer dependencies' :
  command => 'composer update',
  cwd => '/home/vagrant/laravel',
  user => 'vagrant',
  path => '/usr/bin',
  creates => '/home/vagrant/laravel/vendor/swiftmailer',
  require => Exec['add composer dependencies'],
}

exec { 'change laravel storage path' :
  command => "sed -i \"s:'storage' => __DIR__.'/../app/storage',:'storage' => '/laravel_app_storage',:\" paths.php",
  cwd => '/home/vagrant/laravel/bootstrap',
  path => '/bin',
  require => Exec['create laravel project'],
}

exec { 'change laravel public path' :
  command => "sed -i \"s:'public' => __DIR__.'/../public',:'public' => '/rssdb/public',:\" paths.php",
  cwd => '/home/vagrant/laravel/bootstrap',
  path => '/bin',
  require => Exec['create laravel project'],
}

exec { 'set storage permissions' :
  command => "chmod -R 777 /home/vagrant/laravel/app/storage",
  path => '/bin',
  require => Exec['create laravel project'],
}

exec { 'change laravel app path' :
  command => "sed -i \"s:'app' => __DIR__.'/../app',:'app' => '/rssdb/app',:\" paths.php",
  cwd => '/home/vagrant/laravel/bootstrap',
  path => '/bin',
  require => Exec['create laravel project'],
}

class { 'php::composer': }

php::ini { 'php':
  value   => ['date.timezone = "America/Los_Angeles"'],
  target  => 'php.ini',
  service => 'apache',
}
php::ini { 'custom':
  value   => ['display_errors = On', 'error_reporting = -1'],
  target  => 'custom.ini',
  service => 'apache',
}

class { 'mysql':
  root_password => '2lmq29fhq2ss',
  require       => Exec['apt-get update'],
}

mysql::grant { 'rssdb':
  mysql_privileges     => 'ALL',
  mysql_db             => 'rssdb',
  mysql_user           => 'limited',
  mysql_password       => '2lmq29fhq2ss',
  mysql_host           => '%',
  mysql_grant_filepath => '/home/vagrant/puppet-mysql',
}
