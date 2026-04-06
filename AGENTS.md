# AGENTS

## Dev environment tips
- All development lifecycle commands must be run inside the Docker container.
    - Docker Compose is located at `./docker/docker-compose.yml`
    - The PHP container is named `php`
    - All important commands are defined in Composer, so use the following pattern to run them: `docker-compose --file "docker/docker-compose.yml" run --rm "php" composer run-script {{command}}`
    - Aliases for all commands are also defined in the `Makefile`.
    - Using `make` is always preferable when it is installed in the current environment.
- Commands
    - Install or update all PHP dependencies
        - Docker: `docker-compose --file "docker/docker-compose.yml" run --rm "php" composer update`
        - Make: `make install`
    - Fix files so they follow the code style
        - Docker: `docker-compose --file "docker/docker-compose.yml" run --rm "php" composer run-script fixer`
        - Make: `make fixer`
    - Run the linter (static analysis and code style checks)
        - Docker: `docker-compose --file "docker/docker-compose.yml" run --rm "php" composer run-script linter`
        - Make: `make linter`
    - Run all tests
        - Docker: `docker-compose --file "docker/docker-compose.yml" run --rm "php" composer run-script test`
        - Make: `make test`
    - Run all tests with code coverage (results will be stored in HTML format in `./tests/coverage`)
        - Docker: `docker-compose --file "docker/docker-compose.yml" run --rm "php" composer run-script coverage`
        - Make: `make coverage`
    - Run mutation testing
        - Docker: `docker-compose --file "docker/docker-compose.yml" run --rm "php" composer run-script infection`
        - Make: `make infection`
    - Run all required checks in one command (`fixer` + `linter` + `test` + `infection`)
        - Docker: `docker-compose --file "docker/docker-compose.yml" run --rm "php" composer run-script release`
        - Make: `make release`
    - Start a Bash session in the container environment
        - Docker: `docker-compose --file "docker/docker-compose.yml" run --rm "php" bash`
        - Make: `make shell`

## Testing instructions
- To verify that a change is correct, run the following commands in order. If any command fails, stop and fix the issue before continuing.
    - Fix files so they follow the code style
    - Run all tests
    - Run the linter
    - Run mutation testing
- The library uses PHPUnit and follows standard patterns.
    - Tests are located in `./tests`
    - The folder structure must match the structure in `./src`
    - All test classes must extend `Marvin255\ValueObject\Tests\BaseCase`
    - There must be one test class for each production class
- All new changes must be covered by unit tests.
- After the change, make sure that ./README.md is also updated accordingly.
