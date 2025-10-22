config = {
    'app': 'owncloud-updater-server',
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

    return before + stages + build

def beforePipelines():
    return []

def stagePipelines():
    return [phpunit(), integration()]

def buildPipelines():
    return [build()]


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

def dependsOn(earlierStages, nextStages):
    for earlierStage in earlierStages:
        for nextStage in nextStages:
            nextStage['depends_on'].append(earlierStage['name'])
