This is the server that is called from ownCloud to check if a new version of the server is available.

## How to release a new update

1. Adjust config/config.php for the update
2. Adjust tests/integration/features/update.feature for the integration tests

If the tests are not passing the TravisCI test execution will fail.

## Example calls

Deployed URL: http://updates.owncloud.com/server/
Example call: update-server/?version=8x2x0x12x1448709225.0768x1448709281xstablexx2015-10-19T18:44:30+00:00%208ee2009de36e01a9866404f07722892f84c16e3e
```xml
<?xml version="1.0"?>
<owncloud>
  <version>8.2.1.4</version>
  <versionstring>ownCloud 8.2.1</versionstring>
  <url>https://download.owncloud.org/community/owncloud-8.2.1.zip</url>
  <web>https://doc.owncloud.org/server/8.1/admin_manual/maintenance/upgrade.html</web>
</owncloud>
```

## Testing against a production updater server instance
`make test-production`


## Testing against any updater server instance
```bash
cd tests/integration
SERVER_PROTO="http" SERVER_HOST="example.org:8080/whatever" ../../vendor/bin/behat
```

## Testing with a temporary local server
`make test`

Run a single scenario only (the scenario starting in line 4 of the feature file):

`make test BEHAT_FEATURE=features/update.daily.feature:4`

## Testing the docker container (with php8)

FIXME: The template expects a huge list of OWNCLOUD_UPDATER_SERVER_* variables. We have no code to convert a config.php into such a list.
Workaround: Replace the template with a real config.php file.
```
docker build -f Dockerfile -t server-updater:php8 . --pull=true
docker run --rm -v $(pwd)/config.config.php:/etc/templates/owncloud-updater-server.php.tmpl -ti server-updater:php8
```

From another shell, try

```bash
container=$(docker ps | grep server-updater:php8 | sed -e 's/\s.*//')
ipaddr=$(docker inspect $container | jq '.[0].NetworkSettings.IPAddress' -r)
channel=production
```

`curl "http://$ipaddr:8080/server/?version=10x7x0x4x0x0x${channel}xCommunityx"`
```
<?xml version="1.0" encoding="UTF-8"?>
<owncloud>
 <version>10.8.0</version>
 <versionstring>ownCloud 10.8.0</versionstring>
 <url>https://download.owncloud.org/community/owncloud-10.8.0.zip</url>
 <web>https://doc.owncloud.org/server/10.7/admin_manual/maintenance/upgrade.html</web>
</owncloud>
```

`curl "http://$ipaddr:8080/server/?version=10x8x0x4x0x0x${channel}xCommunityx"`

```
<?xml version="1.0" encoding="UTF-8"?>
<owncloud>
 <version>10.10.0</version>
 <versionstring>ownCloud 10.10.0</versionstring>
 <url>https://download.owncloud.com/server/stable/owncloud-10.10.0.zip</url>
 <web>https://doc.owncloud.org/server/10.8/admin_manual/maintenance/upgrade.html</web>
</owncloud>
```

```bash
cd tests/integration
SERVER_PROTO="http" SERVER_HOST="$ipaddr:8080/server" ../../vendor/bin/behat
```
