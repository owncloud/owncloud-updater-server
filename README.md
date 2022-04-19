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

## Docker usage

### Variables used in overlay/etc/templates/owncloud-updater-server.php.tmpl

 - "OWNCLOUD_UPDATER_SERVER_CHANNELLIST 			- whitespace separated list of channels
 - "OWNCLOUD_UPDATER_SERVER_" $cha "_VERSIONS			- whitespace separated list of versions in a channel
 - "OWNCLOUD_UPDATER_SERVER_" $cha "_" $ver "_LATEST" 		- the 'latest' value for a version and channel combination.
 - "OWNCLOUD_UPDATER_SERVER_" $cha "_" $ver "_WEB"		- the 'web' value ...
 - "OWNCLOUD_UPDATER_SERVER_" $cha "_" $ver "_DOWNLOAD_URL" 	- the 'downloadUrl' value ...

### Example

Using a real config.php instead of the above variable decomposition, and
using a minimalistic query string.

```
docker build -t owncloud-updater-server .
docker run -ti -p 8080:8080 -v $PWD/config/config.php:/etc/templates/owncloud-updater-server.php.tmpl owncloud-updater-server
curl 'http://localhost:8080/?version=10x7x0x12xxxstablexx'
```
```
<?xml version="1.0" encoding="UTF-8"?>
<owncloud>
<version>10.8.0</version>
<versionstring>ownCloud 10.8.0</versionstring>
<url>https://download.owncloud.org/community/owncloud-10.8.0.zip</url>
<web>https://doc.owncloud.org/server/10.7/admin_manual/maintenance/upgrade.html</web>
</owncloud>
```

