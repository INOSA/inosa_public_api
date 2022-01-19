pipeline {
    agent any

    environment {
        CI_BASE_URL = "ci.vadev.net"
        PROJECT_KEY = "public-api.inosa" // used for creating containers
        PROJECT_NAME = "Inosa Public Api" // used for notifications

        SONAR_HOST = "https://sonar.ci.vadev.net"
        SONAR_LOGIN = credentials('sonar-login')
        SONAR_PROJECT_KEY = "inosa:public-api"
        SONAR_SOURCES = "./src"
        SONAR_INCLUSIONS = "**/*.php"
        SONAR_EXCLUSIONS = "./vendor/**"

        GITHUB_REPO = "INOSA/inosa_public_api"
        GITHUB_ACCESS_TOKEN = credentials('valueadd-robot-github-token')

        SLACK_TEAM_DOMAIN = "valueadd-team"
        SLACK_CHANNEL = "inosa-builds"
    }

    options {
        quietPeriod(0)

        buildDiscarder(logRotator(numToKeepStr: '20'))

        disableConcurrentBuilds()

        timestamps()
    }

    stages {

        stage ('Environment') {
            when {
                changeRequest()
            }
            steps{
                script {
                    def regex = "(.*Test.*|\\.idea.*|resources/lang.*)"
                    def regex2 = ".*php"
                    def filecollection = pullRequest.files.collect{ it.filename }.findAll{ !(it ==~ regex)}.findAll{ it ==~ regex2 }.findAll{ fileExists(it) }
                    env.CHANGED_FILES = filecollection.join(' ')
                    env.CHANGED_FILES_COMMA = filecollection.join(',')
                    sh "echo ${CHANGED_FILES}"
                }
            }
        }

        stage ('Build static tools image') {
            steps {
                sh "docker-compose -f docker-compose.test.yml up -d --build"
                sh "while ! docker-compose -f docker-compose.test.yml logs testdb | grep 'DATABASE IS READY'; do sleep 1; done"
                sh "docker logs public_api_testapi_1"
//                 sh "docker-compose -f docker-compose.test.yml exec -T testapi bin/console doctrine:migrations:migrate -n"
//                 sh "docker-compose -f docker-compose.test.yml exec -T testapi bin/console doctrine:fixtures:load -n"
            }
        }

        stage ('Static tools') {
            when {
                changeRequest()
            }

            failFast false

            parallel {
                stage ('UNIT') {
                    steps {
                        githubNotify credentialsId: 'github-app-inosa', context: 'PHPUNIT', description: 'Starting phpunit...',  status: 'PENDING'
                        sh "docker-compose -f docker-compose.test.yml exec -T testapi sh ./scripts/test-unit.sh"
                    }

                    post {
                        success {
                            githubNotify credentialsId: 'github-app-inosa', context: 'PHPUNIT', description: 'PHPUNIT success!',  status: 'SUCCESS'
                        }
                        failure {
                            githubNotify credentialsId: 'github-app-inosa', context: 'PHPUNIT', description: 'PHPUNIT failed',  status: 'FAILURE'
                        }
                    }
                }

                stage ('STAN') {
                    steps {
                        githubNotify credentialsId: 'github-app-inosa', context: 'PHPSTAN', description: 'Starting stan analysis...',  status: 'PENDING'
                        sh "docker-compose -f docker-compose.test.yml exec -T testapi sh ./scripts/phpstan.sh"
                    }
                    post {
                        success {
                            githubNotify credentialsId: 'github-app-inosa', context: 'PHPSTAN', description: 'PHPStan success!',  status: 'SUCCESS'
                        }
                        failure {
                            githubNotify credentialsId: 'github-app-inosa', context: 'PHPSTAN', description: 'PHPStan failed',  status: 'FAILURE'
                        }
                    }
                }

                stage ('BRANCH NAME') {
                    steps {
                        githubNotify credentialsId: 'github-app-inosa', context: 'name check', description: 'Checking commit naming',  status: 'PENDING'
                        git branch: "${env.CHANGE_BRANCH}", url: 'git@github.com:INOSA/inosa_public_api.git' , credentialsId: 'valueadd-robot-github-ssh'
                        sh "scripts/git_naming_check.sh ${env.CHANGE_TARGET} ${env.CHANGE_BRANCH}"
                    }
                    post {
                        failure {
                            githubNotify credentialsId: 'github-app-inosa', context: 'name check', description: 'commit names not OK',  status: 'FAILURE'
                        }

                        success {
                            githubNotify credentialsId: 'github-app-inosa', context: 'name check', description: 'commit names OK',  status: 'SUCCESS'
                        }
                    }
                }

                stage ('PHPCS') {
                    agent {
                        dockerfile {
                            filename 'Dockerfile.jakzal'
                        }
                    }

                    steps {
                        githubNotify credentialsId: 'github-app-inosa', context: 'PHP-Code-Standards', description: 'Checking code standards',  status: 'PENDING'
                        catchError(buildResult: 'SUCCESS', stageResult: 'FAILURE') {
                            sh 'composer global require slevomat/coding-standard'
                            sh '/tools/.composer/vendor/bin/phpcs --standard=phpcs.xml --extensions=php --tab-width=4 -sp src tests --report-checkstyle=phpcs-report.xml --report-code'
                        }
                        stash name: 'phpcs-report', includes: 'phpcs-report.xml'
                    }

                    post {
                        always {
                            cleanWs()
                        }
                        failure {
                            githubNotify credentialsId: 'github-app-inosa', context: 'PHP-Code-Standards', description: 'Fix your coding standards',  status: 'FAILURE'
                        }

                        success {
                            githubNotify credentialsId: 'github-app-inosa', context: 'PHP-Code-Standards', description: 'All good with coding standards',  status: 'SUCCESS'
                        }
                    }
                }

                stage ('LINT') {
                    agent {
                        dockerfile {
                            filename 'Dockerfile.jakzal'
                        }
                    }

                    steps {
                        githubNotify credentialsId: 'github-app-inosa', context: 'parallel-lint', description: 'Checking file syntax',  status: 'PENDING'
                        catchError(buildResult: 'SUCCESS', stageResult: 'FAILURE') {
                            sh 'composer global require php-parallel-lint/php-parallel-lint'
                            sh '/tools/.composer/vendor/bin/parallel-lint --checkstyle src > parallel-report.xml'
                        }
                        stash name: 'parallel-report', includes: 'parallel-report.xml'
                    }

                    post {
                        always {
                            cleanWs()
                        }
                        failure {
                            githubNotify credentialsId: 'github-app-inosa', context: 'parallel-lint', description: 'Found errors',  status: 'FAILURE'
                        }

                        success {
                            githubNotify credentialsId: 'github-app-inosa', context: 'parallel-lint', description: 'Everything good',  status: 'SUCCESS'
                        }
                    }
                }

                stage ('COMPOSER VALIDATE') {
                    agent {
                        dockerfile {
                            filename 'Dockerfile.jakzal'
                        }
                    }

                    steps {
                        githubNotify credentialsId: 'github-app-inosa', context: 'Composer validate', description: 'validating...',  status: 'PENDING'
                        catchError(buildResult: 'SUCCESS', stageResult: 'FAILURE') {
                            sh 'composer validate --no-check-publish'
                        }
                    }

                    post {
                        always {
                            cleanWs()
                        }
                        failure {
                            githubNotify credentialsId: 'github-app-inosa', context: 'Composer validate', description: 'Validation failed',  status: 'FAILURE'
                        }

                        success {
                            githubNotify credentialsId: 'github-app-inosa', context: 'Composer validate', description: 'Validation passed',  status: 'SUCCESS'
                        }
                    }
                }
            }

            post {
                always {
                    unstash name: 'phpcs-report'
                    unstash name: 'parallel-report'

                    ViolationsToGitHub([
                        gitHubUrl: 'https://api.github.com/',
                        repositoryOwner: 'INOSA',
                        repositoryName: 'inosa_html5_api',
                        pullRequestId: '${CHANGE_ID}',
                        oAuth2Token: "${GITHUB_ACCESS_TOKEN}",

                        createSingleFileComments: true,
                        minSeverity: 'INFO',
                        maxNumberOfViolations: 99999,
                        keepOldComments: false,

                        commentTemplate: '''
**Reporter**: {{violation.reporter}}{{#violation.rule}}

**Rule**: {{violation.rule}}{{/violation.rule}}
**Severity**: {{violation.severity}}
**File**: {{violation.file}} L{{violation.startLine}}{{#violation.source}}

**Source**: {{violation.source}}{{/violation.source}}

{{violation.message}}
''',
                        violationConfigs: [
                                [ pattern: '.*/checkstyle.*\\.xml$', parser: 'CHECKSTYLE', reporter: 'PSALM' ],
                                [ pattern: '.*/checkstyle2.*\\.xml$', parser: 'CHECKSTYLE', reporter: 'PSALM' ],
                                [ pattern: '.*/phpcs-report.*\\.xml$', parser: 'CHECKSTYLE', reporter: 'PHPCS' ],
                                [ pattern: '.*/phpcs2-report.*\\.xml$', parser: 'CHECKSTYLE', reporter: 'PHPCS' ],
                                [ pattern: '.*/parallel-report.*\\.xml$', parser: 'CHECKSTYLE', reporter: 'parallel-lint' ]
                        ]
                    ])
                }
            }
        }
    }

    post {
        always {
            sh "docker-compose -f docker-compose.test.yml down -v --rmi local"
            cleanWs()
        }
    }
}

def send_slack_notification(build, boolean success = true, stage = '') {
    echo "[SlackNotification] Sending slack meessage to #${SLACK_CHANNEL} channel in ${SLACK_TEAM_DOMAIN} team."
    def color = ''
    def message = "*Project:* ${PROJECT_NAME}\n"
    message += "*Branch:* ${env.BRANCH_NAME}\n"
    message += "*Build:* ${build.displayName}\n"
    message += "*Duration:* ${build.durationString.replace(' and counting', '')}\n"

    if (success) {
        color = '#27ae60'
        message += "*Status:* SUCCESS"
    } else {
        color = '#c0392b'
        message += "*Status:* FAILED\n"
        message += "*Failed on:* ${stage}\n"
        message += "Check full log ${build.absoluteUrl}"
    }

    slackSend([
            channel   : SLACK_CHANNEL,
            color     : color,
            message   : message,
            teamDomain: SLACK_TEAM_DOMAIN
    ])
}

