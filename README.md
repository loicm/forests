# Forests

L'Astrée, première partie, livre premier :

> Aupres de l’ancienne ville de Lyon, du costé du soleil couchant, il y a un pays nomme Forests, qui en sa petitesse contient ce qui est de plus rare au reste des Gaules, car estant divisé en plaines et en montaignes, les unes et les autres sont si fertiles, et situées en un air si temperé, que la terre y est capable de tout ce que peut desirer le laboureur.

## Usage

Forests is a simple static blog generator.

Start by renaming `.env-sample` to `.env`, then adapt the `.env` to your needs.

Run `composer install` to install dependencies.

There are two commands:

```
bash
# Generate the site on your machine
./forests site:build

# Deploy on your server with rsync
./forests site:deploy
```

## Content

You will deal with the following types of content:

- Notes = articles
- Pages

### Structure your content

It's better to store your content files outside `forests` so you can easily use git to version your data.

```
- carnet/
    - notes/
        - 2014/01/01/note-slug.md (YYYY/MM/DD/slug.md)
    - pages/
        - page-slug.md
```

Output directory is where `./forests site:build` will generate your static site on your machine

```
- carnet_html/
```
