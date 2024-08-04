<h1>About</h1>

<div class="text-container">
    <h2 id="story">The Story</h2>

    <p>
        Back when I was in school I watched <a href="https://en.wikipedia.org/wiki/The_Social_Network">
        The Social Network movie</a>. I was fascinates by the scene in which Zuckerberg implemented Facemash in an
        eventing. So, I was kinda curious if I could manage that too. I didn't. I took me 3 days to implement a very
        <a href="https://github.com/overflowerror/promash">simple version</a>. (Writing this now, I also found out
        that it took Zuckerberg
        <a href="https://www.thecrimson.com/article/2003/11/19/facemash-creator-survives-ad-board-the/">a week</a>
        - not just an evening. Welp.) Instead of students, my version used pictures of my professors, that I scraped
        from my school's website - which, in retrospect, was morally a bit problematic.
    </p>
    <p>
        Recently, I found the old source code again, and I was thinking: Maybe I can rewrite it. Let's not use humans
        this time, and instead use something nerdy: Minecraft mobs. And while we are at it, let's also do it properly
        - the previous version really was quite bad from a technical perspective.
    </p>

    <h2 id="implementation">Implementation</h2>

    <p>
        The basic idea is to give the users the choice between mobs. The better one is selected and its internal
        rating increases. The algorithm used is the <a href="https://en.wikipedia.org/wiki/Elo_rating_system">Elo
        rating system</a>. I won't go into details here, but the basic idea is the following: It works by assigning
        each candidate a number, and calculate the winning probabilities for each match. This winning probability
        then determines how many points are gained/lost by each candidate.
    </p>
    <p>
        We can also use this rating to calculate the idea pairing. Mobs that have a similar rating might be more
        engaging to compare than mobs where it's obvious which one is better.
    </p>
    <p>
        The challenge here is twofold: First, we need to be able to asynchronously calculate and write the ratings to
        the database. This could theoretically be solved by utilizing transactions. The other problem is, that I want
        to be able to delete entries from the database in case I detect spam or "cheating". So, we need a list of all
        changes, so we can undo them if necessary - which would also mean we need to recompute all ratings.
    </p>
    <p>
        The way I chose to solve those problems, is by doing the calculations in on-the-fly in the database. The
        only thing we need to store, is the match-ups including the winner and the date for sorting. The rating
        calculation is then done by starting from a base rating for each mob (1500 in this case) and applying each
        match to the ratings in sequence using a recursive query. (Initially, I tried to keep each rating as its
        own row. However, I was not able to make it work. Not sure if that's a limitation of PostgreSQL or if I'm
        just not smart enough. ^^ Either way, the current solution uses <code>jsonb</code> objects for the rating
        state - which also turned out to be much faster than having each rating as its own object.)
    </p>
    <p>
        This approach works quite well and can handle a few thousands of votes with no issues. However, since we
        need to iterate over all matches every time we want to calculate the next pairing, it doesn't scale well
        beyond that. My solution for this was to introduce a caching table, that contains a recent snapshot of
        the ratings. The recursive calculation than seeds itself with the cache instead of starting from the
        beginning. This cache can be generated regularly (maybe once a day) to keep the responses snappy.
    </p>
    <p>
        The flip side of using this cache is, of course, that in case I need to delete some matches, I also need
        to delete the corresponding cache entries. It's not a huge deal, but certainly something to be aware of.
    </p>
    <p>
        If you want to learn more, check out the source code over on
        <a href="https://github.com/overflowerror/mobmash.click">Github</a>. Pull Requests are welcome!
    </p>

    <h2 id="credits">Credits & Tech Stack</h2>

    <h3>Minecraft Related Content</h3>

    <p>
        Minecraft content and materials are trademarks and copyrights of <a href="https://www.minecraft.net/">Mojang Studios</a>.
    </p>

    <h3>Fonts</h3>

    <ul>
        <li><a href="https://www.fontspace.com/minecraft-font-f28180">Minecraft Font by JDGraphics</a> (Public Domain)</li>
        <li><a href="http://fontawesome.io">Font Awesome v4.7.0</a> (OFL-1.1 & MIT)</li>
    </ul>

    <h3>Frontend Frameworks</h3>

    <ul>
        <li><a href="https://htmx.org/">HTMX</a> (0-Clause BSD)</li>
        <li><a href="https://www.chartjs.org/">Chart.js</a> (MIT)</li>
    </ul>


    <h3>Backend Technologies</h3>

    <ul>
        <li><a href="https://www.php.net/">PHP</a></li>
        <li><a href="https://www.postgresql.org/">PostgreSQL</a></li>
    </ul>

    <h2 id="thanks">Special Thanks</h2>

    <p>
        I'd like to thank the <a href="https://minecraft.wiki/">Minecraft Wiki</a> for letting me let use their API to
        automatically update the mobs in the vote. The pictures for the mobs are also provided by them.
    </p>
    <p>
        Also, I'm very, very bad at design. So a big thanks goes to the web designer who helped me! (They would like
        to stay anonymous. Apparently, the design is not good enough. ^^)
    </p>

</div>