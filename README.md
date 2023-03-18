# Laravel Post API

## Add Passport Authentication

Add passport dependency via composer

```bash
composer require laravel/passport
```

Migrate then generate clients

```bash
php artisan migrate
php artisan passport:install
```

Example clients after passport:install

```bash
# php artisan passport:install
Encryption keys generated successfully.
Personal access client created successfully.
Client ID: 1
Client secret: WnQYw9c5SHkIeqSbMwsWPLFtXGB2wiahl6RbPvGN
Password grant client created successfully.
Client ID: 2
Client secret: a9SOtcG00YM3eaD2M3AzbBhmXUkB87BYXxeNBL1h
```

Configure private key and public key in .env

```bash
php artisan vendor:publish --tag=passport-config
# .env
# PASSPORT_PRIVATE_KEY=
# PASSPORT_PUBLIC_KEY=
```

Create user via tinker command

```bash
php artisan tinker --execute "User::create(['name'=>'Super Admin','email'=>'adminz@gmail.com','password'=>bcrypt(\"123123\")])"
```

Test passport clients via curl

```bash
curl --location "http://localhost:8000/oauth/token" \
--header "Content-Type: application/json" \
--data-raw "{
    \"grant_type\": \"password\",
    \"client_id\": \"2\",
    \"client_secret\": \"a9SOtcG00YM3eaD2M3AzbBhmXUkB87BYXxeNBL1h\",
    \"username\": \"adminz@gmail.com\",
    \"password\": \"123123\"
}"
```

Create unit test

```bash
php artisan make:test PassportTest

./vendor/bin/phpunit --filter=PassportTest
composer test --filter=PassportTest
php artisan test
```
