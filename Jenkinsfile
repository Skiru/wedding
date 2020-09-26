#!/usr/bin/env groovy

pipeline {
    environment {
        HOME = "${WORKSPACE}"
        REGISTRY = "mkoziol/purpleclouds"
        REGISTRY_CREDENTIALS = 'dockerhub'
        PHP_IMAGE = ""
        ASSETS_IMAGE = ""
        PHP_IMAGE_NAME = "wedding-php"
        ASSETS_IMAGE_NAME = "wedding-assets"
        FULL_PHP_IMAGE_NAME = "${REGISTRY}:${PHP_IMAGE_NAME}-${BUILD_NUMBER}"
        FULL_ASSETS_IMAGE_NAME = "${REGISTRY}:${ASSETS_IMAGE_NAME}-${BUILD_NUMBER}"
    }

    agent any

    stages {

        stage('Clean environment') {
            steps{
                sh '''
                git reset --hard HEAD
                git clean -fdx
                '''
            }
        }

        stage('Get code from SCM') {
            steps{
                checkout(
                    [$class: 'GitSCM', branches: [[name: '*/master']],
                     doGenerateSubmoduleConfigurations: false,
                     extensions: [],
                     submoduleCfg: [],
                     userRemoteConfigs: [[credentialsId: 'blog-repository', url: "git@github.com:Skiru/wedding.git"]]]
                )
            }
        }

        stage('Building php image') {
          steps{
            script {
              PHP_IMAGE = docker.build(FULL_PHP_IMAGE_NAME, "-f ./docker/php/Dockerfile . --no-cache")
            }
          }
        }

        stage('Building assets image') {
          steps{
            script {
              ASSETS_IMAGE = docker.build(FULL_ASSETS_IMAGE_NAME, "-f ./docker/assets/Dockerfile . --no-cache")
            }
          }
        }

        stage('Deploy php image to dockerhub') {
            steps{
                script {
                  docker.withRegistry( '', REGISTRY_CREDENTIALS ) {
                    PHP_IMAGE.push()
                  }
                }
           }
        }

        stage('Deploy assets image to dockerhub') {
            steps{
                script {
                  docker.withRegistry('', REGISTRY_CREDENTIALS ) {
                    ASSETS_IMAGE.push()
                  }
                }
           }
        }

        stage('Remove Unused docker image') {
          steps{
            sh "docker rmi ${env.FULL_PHP_IMAGE_NAME}"
            sh "docker rmi ${env.FULL_ASSETS_IMAGE_NAME}"
            sh "docker image prune -f"
          }
        }

        stage('Build wedding application') {
            steps{
                sshagent (credentials: ['purple-clouds-server']) {
                    sh 'echo "docker login --username mkoziol --password pamietamhaslo;WEDDING_PHP_IMAGE_BUILD_TAG=$FULL_PHP_IMAGE_NAME; export WEDDING_PHP_IMAGE_BUILD_TAG; WEDDING_ASSETS_IMAGE_BUILD_TAG=$FULL_ASSETS_IMAGE_NAME; export WEDDING_ASSETS_IMAGE_BUILD_TAG; docker-compose -f /var/www/PurpleClouds/wedding/docker-compose.yml up -d;" | ssh -o StrictHostKeyChecking=no -l root 77.55.222.35'
                }
            }
        }

    }
}
