#!/bin/sh

cd /var/www/mobmash

# Build JS
cd resources/js/
npm install
npm run build
cd /var/www/mobmash

# Fetch Emoji
./bin/setup/fetch-emoji.sh

# Prepare Config
cp config.templ.php config.php
sed -i -E 's/%DBHOST%/'"$POSTGRES_HOST"'/g' config.php
sed -i -E 's/%DBNAME%/'"$POSTGRES_DBNAME"'/g' config.php
sed -i -E 's/%DBUSER%/'"$POSTGRES_USER"'/g' config.php
sed -i -E 's/%DBPASSWORD%/'"$POSTGRES_PASSWORD"'/g' config.php
sed -i -E 's/%UPDATER_EMAIL%/'"$UPDATER_CONTACT_EMAIL"'/g' config.php
sed -i -E 's/%GENERAL_EMAIL%/'"$GENERAL_CONTACT_EMAIL"'/g' config.php
sed -i -E 's/%PRIVACY_CONTACT%/'"$PRIVACY_CONTACT"'/g' config.php
sed -i -E 's/%PRIVACY_EMAIL%/'"$PRIVACY_CONTACT_EMAIL"'/g' config.php

# Fetch MC Wiki data (if necessary)
test -f ./html/images/mobs/cow.png || php ./bin/cron/updateData.php

# Start dev server
php -S 0.0.0.0:8080 -t /var/www/mobmash/html