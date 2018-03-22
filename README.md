menu
====

A Symfony3 project created on October 31, 2016, 1:37 pm.

### Requirements

Front
----
```bash
sudo npm install -g bower
sudo su -c "gem install sass"
sudo gem install sass
sudo gem update --system

# Example to install lib with bower
bower install --save fontawesome
```

### FOSJsRoutingBundle
```bash
bin/console fos:js-routing:dump --env=dev
```

### Fixtures
```bash
bin/console doctrine:fixtures:load
```

### Assets
```bash
bin/console assets:install --symlink
```

### Régler pb de droit sur fichier cache et logs
```bash
sudo rm -rf var/cache/* && sudo rm -rf var/logs/* && sudo rm -rf var/sessions/* && HTTPDUSER=`ps aux | grep -E '[a]pache| && [h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1` && sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var/cache var/logs var/sessions && sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var/cache var/logs var/sessions
```
