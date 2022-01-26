INOSA Public API

thephpleague/oauth2-server-bundle

Commands for creation of a new application can be found here:
```
https://github.com/trikoder/oauth2-bundle/blob/v3.x/docs/basic-setup.md
```

# Tests
###In order to run tests locally

Go to root folder of this repository and run following script to build test container.

`./scripts/create-local-test-container.sh` - wait until the process is finished

You can now run following static analysis tools:

### Unit
`./scripts/local/unit.sh`

### PHPStan
`./scripts/local/phpstan.sh`

### PHPCS
`./scripts/local/phpcs.sh`

### Removing test containers is done via:
`./scripts/remove-local-test-container.sh`


## For local development
If you have set up fixtures for main api application you can run 
`./scripts/public-api-client-create.sh` 

This will create complete public api client account connected with main api user called *public-api-user*.
