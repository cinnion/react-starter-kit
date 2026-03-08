pipeline {
    agent any
    environment {
        BUILD_DATE_TIME = "${sh(script: 'date +%Y-%m-%d\\ %H:%M:%S\\ %Z', returnStdout: true).trim()}"
        REGISTRY = 'registry.ka8zrt.com:5000'
        TAG = "${env.BUILD_ID}"
        DOCKER_NODE = "docker"
    }
    stages {
        stage('Generate Build Info JSON') {
            steps {
                script {
                    // Define a Groovy map with the build information
                    def buildInfo = [
                        'BUILD_NUMBER': env.BUILD_NUMBER,
                        'BUILD_ID': env.BUILD_ID,
                        'JOB_NAME': env.JOB_NAME,
                        'BUILD_URL': env.BUILD_URL,
                        'GIT_COMMIT': env.GIT_COMMIT,
                        'GIT_BRANCH': env.GIT_BRANCH,
                        'BUILD_DATE': env.BUILD_DATE_TIME,
                    ]

                    // Convert the Groovy map to a JSON formatted string
                    def jsonString = groovy.json.JsonOutput.prettyPrint(groovy.json.JsonOutput.toJson(buildInfo))

                    // Write the JSON string to a file named 'build_info.json' in the workspace
                    writeFile(file: 'build_info.json', text: jsonString)
                }
            }
        }

        stage('Resolve docker-compose file') {
            steps {
                script {
                    // Read the docker-compose-prod file
                    def template = readFile 'docker-compose-prod.yml'

                    // Replace the variables for interior production
                    def resolvedContentInternal = template.replaceAll(/\$\{REGISTRY\}/, env.REGISTRY)
                                                  .replaceAll(/\$\{TAG\}/, env.TAG)
                                                  .replaceAll(/\$\{DOCKER_NODE\}/, 'docker')

                    // Write the resolved content to a new file for interior production
                    writeFile(file: 'laravel-docker.yml', text: resolvedContentInternal)

                    // Replace the variables for exterior production
                    def resolvedContentExternal = template.replaceAll(/\$\{REGISTRY\}/, env.REGISTRY)
                                                  .replaceAll(/\$\{TAG\}/, env.TAG)
                                                  .replaceAll(/\$\{DOCKER_NODE\}/, 'beta')

                    // Write the resolved content to a new file for exterior production
                    writeFile(file: 'laravel-beta.yml', text: resolvedContentExternal)
                }
            }
        }

        stage('Build') {
            steps {
                sh 'docker compose -f docker-compose-prod.yml -p laravel build'
            }
        }

        stage('Push') {
            steps {
                sh "docker push ${REGISTRY}/laravel-php-fpm:${TAG}"
                sh "docker push ${REGISTRY}/laravel-php-fpm:latest"
                sh "docker push ${REGISTRY}/laravel-web:${TAG}"
                sh "docker push ${REGISTRY}/laravel-web:latest"
            }
        }

        stage('Deploy') {
            steps {
                sshagent(credentials: ['jenkins-ssh']) {
                    sh '''
                        scp -p laravel-docker.yml root@docker:~/docker-compose/laravel.yml
                        ssh root@docker 'docker stack deploy --detach -c ~/docker-compose/laravel.yml laravel'

                        scp -p laravel-beta.yml root@beta:~/docker-compose/laravel.yml
                        ssh root@beta 'docker stack deploy --detach -c ~/docker-compose/laravel.yml laravel'
                    '''
                }
            }
        }
    }
}
