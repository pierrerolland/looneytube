# looneytube
_My little YouTube for kids_

## Prerequisites

You need to have an environment with PHP >= 7.4 and Node.js (with NPM)

## Setup

Clone the repo

```
git clone https://github.com/pierrerolland/looneytube.git
```

### Setup the API

#### Get composer

```
php composer-setup.php --install-dir=bin --filename=composer
```

#### Setup the JWT keys

Keep your passphrase

```
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
chown www-data config/jwt/*
```

#### Change the "admin" password

In `security.yaml`, change the encoded password for "admin"

#### Setup your env

```
# api/.env.local

APP_ENV=prod
APP_SECRET=somesecret
VIDEOS_DIR=videos # From the public dir
ALLOWED_ORIGIN=https://looneytube.tv # The URL of your client
JWT_PASSPHRASE=pass # Your JWT passphrase
```

#### Install

Run

```
composer install
```

If you see messages regarding uninstalled PHP extensions, install them and proceed

#### Serve

Either configure an Nginx to serve your PHP app, or use the Symfony executable: https://symfony.com/download (`symfony server:run`)

### Setup the client

#### Setup your env

```
# front/.env

API_URL=https://api.looneytube.tv
```

#### In dev mode

```
npm run dev
```

#### In prod mode

```
npm run build
npm run start
```

## Add videos

You can organize videos in a one-level hierarchy of directories.

```
videos root
└── Animated series 1
    └── thumb.png
    └── episode 1.mp4
    └── episode 2.mp4
    └── thumbs
        └── episode 1.jpg
        └── episode 2.jpg
└── Animated series 2
    └── thumb.png
    └── episode 3.mp4
    └── thumbs
        └── episode 3.jpg
```

## Use the helpers

:warning: You need `ffmpeg` for these operations to work

### To generate the thumbnails of the episodes

```
php api/bin/console app:create-thumbs <directory_containing_the_episodes>
```

### To convert the episodes to MP4

MP4 is more likely to be read on browsers

```
php api/bin/console app:convert <directory_containing_the_episodes>
```

Don't forget to remove the old files after


