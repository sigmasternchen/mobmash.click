# MobMash

This project aims to unravel the answer to the gargantuan query of existential magnitude: Which is the best Minecraft mob?

Visitors are prompted with two mobs and should decide which one they like better. After they choose, the "looser" is replaced with a new candidate. In the background, an Elo-style rating system is keeping track of the individual matches. The rating also determines the next candidate - similar rating are paired up. Within one session, pairings are not repeated.

## Tech Stack

- [Chart.js](https://www.chartjs.org/) (MIT)
- [HTMX](https://htmx.org/) (0-Clause BSD)
- [PHP](https://www.php.net/)
- [PostgreSQL](https://www.postgresql.org/)

## Credits

Minecraft content and materials are trademarks and copyrights of [Mojang Studios](https://www.minecraft.net/).

The mob names and images are fetched from the [Minecraft Wiki](https://minecraft.wiki/) (CC BY-NC-SA 3.0). - Thank you for letting me use your API!

The font used is the [Minecraft Font by JDGraphics](https://www.fontspace.com/minecraft-font-f28180) (Public Domain). The icons are [Font Awesome v4.7.0](http://fontawesome.io/) (OFL-1.1 & MIT).
The emoji are [Twemoji](https://github.com/twitter/twemoji/) (CC BY 4.0 & MIT).


## Contribution

Issues and Pull Requests are always welcome!

## Local Development

To develop locally, you need a PostgreSQL database and a PHP 8 interpreter. If you don't have a local web server, you can just use the built-in development server of PHP: `php -S localhost:8080 -t ./html/`

As for configuration, just copy `./config.templ.php` to `./config.php`, fill out the values, and you are ready to go.

The migrations are automatically applied with the first served request.

You will probably have to run `./bin/cron/updateData.php` to create the mob entries in the database and fetch the images. The other files in the `./bin/cron/` are not necessary for most development work, as they will just some cleanup and caching stuff for production.
