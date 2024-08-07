#!/bin/sh

cd "$(dirname "$(dirname "$(dirname "$0")")")"

git clone -b gh-pages --depth=1 https://github.com/twitter/twemoji/

grep ".png" ./html/styles/emoji.css | sed -E 's/.*\/([^/]+)\..*/\1/g' | while read f; do
    cp -v "./twemoji/36x36/$f.png" "./html/images/emoji/"
done

rm -rf twemoji