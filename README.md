# Sms Api Wrapper

Supports MessageBird API out of the box.

To add support of another SMS provider (Twilio for example) create implementation of "Santik\Sms\Infrastructure\SmsClient" interface. 

Install dependencies:
    
     composer install
     
Run tests:

    ./vendor/bin/phpunit tests 

Run application using build-in webserver:
    
    php -S localhost:8000

Send POST request
    
    curl localhost:8000/examples -d '{"recipient":"+1234567890","originator":"originator","message":"message"}' -H 'Content-Type: application/json'