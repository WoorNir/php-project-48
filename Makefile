lint:
	composer exec -- phpcs --standard=PSR12 src bin tests
test:
	composer test
	
test-coverage:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml
