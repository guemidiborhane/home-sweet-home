# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
    config.vm.box = "guemidiborhane/home-sweet-home"
    config.vm.network "private_network", ip: "192.168.33.10"
    config.vm.hostname = "home-sweet-home"
    config.vm.synced_folder "~/Code", "/var/www", :mount_options => ["dmode=777","fmode=666"]
end
