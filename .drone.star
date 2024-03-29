config = {
    'app': 'owncloud-updater-server',
    'rocketchat': {
        'channel': 'server',
        'from_secret': 'private_rocketchat'
    },
    'branches': ['main'],
    'appInstallCommand': 'composer install',
}

# This starlark code is based on the "template" used for core apps
# But it is cut-down here to just what is useful, because this is not a core app
# CI here does not need to be able to install core and...
def main(ctx):
    before = beforePipelines()

    stages = stagePipelines()
    if (stages == False):
        print('Errors detected. Review messages above.')
        return []

    dependsOn(before, stages)

    build = buildPipelines()
    dependsOn(stages, build)

    after = afterPipelines()
    dependsOn(build, after)

    return before + stages + build + after

def beforePipelines():
    return []

def stagePipelines():
    return [phpunit(), integration()]

def buildPipelines():
    return [build()]


def afterPipelines():
    return [notify()]

def phpunit():
    result = {
        'kind': 'pipeline',
        'type': 'docker',
        'name': 'phpunit',
        'steps': [{
            'name': 'test-phpunit',
            'image': 'owncloudci/php:7.4',
            'pull': 'always',
            'commands': [
                'make test-php-unit'
            ],
            'when': {
                'ref': [
                    'refs/pull/**',
                ]
            }
        }],
        'depends_on': [],
        'trigger': {
            'ref': ['refs/pull/**', 'refs/tags/**']
        }
    }

    for branch in config['branches']:
        result['trigger']['ref'].append('refs/heads/%s' % branch)

    return result

def integration():
    result = {
        'kind': 'pipeline',
        'type': 'docker',
        'name': 'behat-integration',
        'steps': [{
            'name': 'test-integration',
            'image': 'owncloudci/php:7.4',
            'pull': 'always',
            'commands': [
                'make test'
            ],
            'when': {
                'ref': [
                    'refs/pull/**',
                ]
            }
        }],
        'depends_on': [],
        'trigger': {
            'ref': ['refs/pull/**', 'refs/tags/**']
        }
    }

    for branch in config['branches']:
        result['trigger']['ref'].append('refs/heads/%s' % branch)

    return result

def build():
    result = {
        'kind': 'pipeline',
        'type': 'docker',
        'name': 'build',
        'steps': [{
            'name': 'docker-dryrun',
            'image': 'plugins/docker',
            'pull': 'always',
            'settings': {
                'dry_run': True,
                'registry': 'registry.owncloud.com',
                'repo': 'registry.owncloud.com/internal/server-updater',
                'tags': 'latest',
            },
            'when': {
                'ref': [
                    'refs/pull/**',
                ]
            }
        }, {
            'name': 'docker',
            'image': 'plugins/docker',
            'pull': 'always',
            'settings': {
                'registry': 'registry.owncloud.com',
                'repo': 'registry.owncloud.com/internal/server-updater',
                'auto_tag': True,
                'username': {
                    'from_secret': 'registry_username',
                },
                'password': {
                    'from_secret': 'registry_password',
                },
            },
            'when': {
                'ref': {
                    'exclude': ['refs/pull/**']
                }
            }
        }],
        'depends_on': [],
        'trigger': {
            'ref': ['refs/pull/**', 'refs/tags/**']
        }
    }

    for branch in config['branches']:
        result['trigger']['ref'].append('refs/heads/%s' % branch)

    return result

def notify():
    result = {
        'kind':
        'pipeline',
        'type':
        'docker',
        'name':
        'chat-notifications',
        'clone': {
            'disable': True
        },
        'steps': [{
            'name': 'notify-rocketchat',
            'image': 'plugins/slack:1',
            'pull': 'always',
            'settings': {
                'webhook': {
                    'from_secret': config['rocketchat']['from_secret']
                },
                'channel': config['rocketchat']['channel']
            }
        }],
        'depends_on': [],
        'trigger': {
            'ref': ['refs/tags/**'],
            'status': ['success', 'failure']
        }
    }

    for branch in config['branches']:
        result['trigger']['ref'].append('refs/heads/%s' % branch)

    return result

def dependsOn(earlierStages, nextStages):
    for earlierStage in earlierStages:
        for nextStage in nextStages:
            nextStage['depends_on'].append(earlierStage['name'])
