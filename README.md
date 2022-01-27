<h1>INOSA Public API</h1>

# Usage

### Create client

`bin/console public-api:client:create <inosa_site_id> clientName`

example:

`bin/console public-api:client:create e3da6082-a61c-4788-8060-5fbd3bd548f7 InosaAs`

Other command for read/update/delete are provided by league server:

Clearing stale tokens:
`league:oauth2-server:clear-expired-tokens  Clears all expired access and/or refresh tokens and/or auth codes`

List all the already created clients: (this will not output siteId)
`league:oauth2-server:list-clients          Lists existing oAuth2 clients`

Delete client:
`league:oauth2-server:delete-client         Deletes an OAuth2 client`

Update client:
`league:oauth2-server:update-client         Updates an OAuth2 client`

For more information how to use them type command --help.

# Tests

### In order to run tests locally

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
