<?php
    $stats ??= [];
    $mobStats ??= [];
?>

<h1>About</h1>

<div class="text-container" data-hx-boost="true">
    <p>
        An arcane mystery as old as time itself: What is the best Minecraft mob?
    </p>
    <p>
        MobMash is a website that lets visitors vote on which mob from the video game Minecraft they like best by
        choosing one of the two mobs presented on the homepage. Based on this relative decision, an algorithm then
        calculates an <a href="/results">absolute rating</a> for the mobs. If you are interested in the technical
        implementation, skip down to the <a href="#implementation">Implementation section</a>, or take a look at the
        source code on <a target="_blank" href="https://github.com/overflowerror/mobmash.click">GitHub</a>.
    </p>

    <h2 id="story">The Story</h2>

    <p>
        Back when I was in school, I watched <a target="_blank" href="https://en.wikipedia.org/wiki/The_Social_Network">
        The Social Network</a> movie. I was fascinates by the scene in which Zuckerberg implemented Facemash in an
        eventing. So, I was kind of curious if I could manage that too - I couldn't. I took me 3 evenings to implement
        even a <a target="_blank" href="https://github.com/overflowerror/promash">rather basic version</a>.
        (Writing this now, I also found out that it took Zuckerberg
        <a target="_blank" href="https://www.thecrimson.com/article/2003/11/19/facemash-creator-survives-ad-board-the/">
        a week</a> - not just an evening. Welp.) Instead of students, my version used pictures of my professors,
        that I scraped from my school's website - which, in retrospect, was morally a bit problematic.
        <i class="emoji grinning-face-with-sweat"></i>
    </p>
    <p>
        Recently, I found the old source code again, and I was thinking: Maybe I can rewrite it. Let's not use humans
        this time. Instead, let's use something nerdy: Minecraft mobs. And while we are at it, let's also do it properly
        - the previous version really was quite bad from a technical perspective.
    </p>

    <h2 id="implementation">Implementation</h2>

    <p>
        The basic idea is to give the users a choice between mobs. The better one is selected, and its internal
        rating increases. The algorithm used is the
        <a target="_blank" href="https://en.wikipedia.org/wiki/Elo_rating_system">Elo rating system</a>. I won't go
        into details here, but the basic idea is the following: It works by assigning each candidate a number and
        calculating the winning probabilities for each match. This winning probability then determines how many
        points are gained/lost by each candidate.
    </p>
    <p>
        We can also use this rating to calculate the ideal pairing. Mobs that have a similar rating might be more
        engaging to compare than mobs where it's obvious which one is better.
    </p>
    <p>
        The challenge here is twofold: First, we need to be able to asynchronously calculate and write the ratings to
        the database. This could theoretically be solved by utilizing transactions. The other problem is that I want
        to be able to delete entries from the database in case I detect spam or "cheating". So, we need a list of all
        changes, so we can undo them if necessary - which would also mean we need to recompute all ratings after
        the deleted ones, as they linearly depend on each other.
    </p>
    <p>
        The way I chose to solve those problems was by doing the calculations in on-the-fly in the database. The
        only thing we have to store are the match-ups, including the winner and the date for sorting. The rating
        calculation is then done by starting from a base rating for each mob (1500 in this case) and applying each
        match to the ratings in sequence using a recursive query. (As a side note: Initially, I tried to keep each
        rating in its own row. However, I was unable to make it work. I'm not sure if that's a limitation of
        PostgreSQL or if I'm just not smart enough. <i class="emoji grinning-face-with-smiling-eyes"></i>
        Either way, the current solution uses <code>jsonb</code> objects for the rating state - which also turned
        out to be much faster than having each rating as its own object.)
    </p>
    <p>
        This approach works quite well and can handle a few thousand votes with no issues. However, since we
        need to iterate over all matches every time we want to calculate the next pairing, it doesn't scale well
        beyond that. My solution for this was to introduce a caching table that contains a recent snapshot of
        the ratings. The recursive calculation then seeds itself with the cache instead of starting from scratch.
        This cache can be generated regularly (maybe once a day) to keep the responses snappy.
    </p>
    <p>
        The flip side of using this cache is, of course, that in case I need to delete some matches, I also have
        to delete the corresponding cache entries. It's not a huge deal, but certainly something to be aware of.
    </p>
    <p>
        If you would like to learn more, I posted an article about the details over on
        <a target="_blank" href="https://blog.sigma-star.io/2024/09/elo-rating-in-pure-sql/">my blog</a>.
        You can also check out the source code on
        <a target="_blank" href="https://github.com/overflowerror/mobmash.click">GitHub</a>. Pull Requests are welcome!
    </p>

    <h2 id="statistics">Statistics</h2>

    <p>
        There are currently <?= number_format($stats["mobs"]) ?> mobs in the system.
        The top-ranked mob is "<?= $mobStats["highest_rating"]["name"] ?>", with a rating of
        <?= number_format($mobStats["highest_rating"]["rating"]) ?>. Last place is
        "<?= $mobStats["lowest_rating"]["name"] ?>" with a rating of
        <?= number_format($mobStats["lowest_rating"]["rating"]) ?>.
    </p>
    <p>
        "<?= $mobStats["most_matches"]["name"] ?>" has fought the most matches:
        <?= number_format($mobStats["most_matches"]["matches"]) ?>, out of which it won
        <?= number_format($mobStats["most_matches"]["wins"]) ?>.
        <?php if ($mobStats["most_matches"]["id"] == $mobStats["most_wins"]["id"]): ?>
            Which also makes it the mob with the most wins.
        <?php else: ?>
            Speaking of wins: The mob with the most wins is "<?= $mobStats["most_wins"]["name"] ?>" with
            <?= number_format($mobStats["most_wins"]["wins"]) ?> wins out of
            <?= number_format($mobStats["most_wins"]["matches"]) ?> matches.
        <?php endif ?>
    </p>
    <p>
        Until now, there have been <?= number_format($stats["votes"]) ?> votes.
    </p>
    <p>
        Over the past 6 months, there have been <?= number_format($stats["voters"]) ?> unique voters.
        On average, each one voted
        <?= number_format($stats["avg"], 1) ?> times, with <?= number_format($stats["max"]) ?>
        <?= $stats["max"] > 80 ? "(You people are mad!)" : "" ?>
        as the maximum.
    </p>
    <p>
        <?php
            if ($stats["maxed_out"] == 0) {
        ?>
            So far, none have voted for all <?= number_format($stats["mobs"] * $stats["mobs"]) ?> pairings yet.
            <i class="emoji disappointed-face"></i>
        <?php
            } else if ($stats["maxed_out"] == 1) {
        ?>
            Coincidentally, that maximum is the highest possible number - this one person voted for every single paring.
             <i class="emoji grinning-face-with-smiling-eyes"></i>
        <?php
            } else {
        ?>
            Coincidentally, that maximum is the highest possible number - <?= number_format($stats["maxed_out"]) ?> visitors voted for every
            single paring. <i class="emoji face-screaming-in-fear"></i>
        <?php
            }
        ?>
    </p>

    <h2 id="contact">Contact</h2>

    <p>
        If you want to contact me regarding this website, please use the following email address:
    </p>
    <p>
        <a href="mailto:<?= GENERAL_CONTACT_EMAIL ?>"><?= GENERAL_CONTACT_EMAIL ?></a>
    </p>

    <h2 id="credits">Credits & Tech Stack</h2>

    <h3>Minecraft-Related Content</h3>

    <p>
        Minecraft content and materials are trademarks and copyrights of
        <a target="_blank" href="https://www.minecraft.net/">Mojang Studios</a>.
    </p>

    <h3>Fonts, Icons, Emoji</h3>

    <ul>
        <li><a target="_blank" href="https://www.fontspace.com/minecraft-font-f28180">Minecraft Font by JDGraphics</a>
            (Public Domain)</li>
        <li><a target="_blank" href="http://fontawesome.io">Font Awesome v4.7.0</a> (OFL-1.1 & MIT)</li>
        <li><a target="_blank" href="https://github.com/twitter/twemoji/">Twemoji</a> (CC BY 4.0 & MIT)</li>
    </ul>

    <h3>Frontend Frameworks</h3>

    <ul>
        <li><a target="_blank" href="https://htmx.org/">HTMX</a> (0-Clause BSD)</li>
        <li><a target="_blank" href="https://www.chartjs.org/">Chart.js</a> (MIT)</li>
    </ul>


    <h3>Backend Technologies</h3>

    <ul>
        <li><a target="_blank" href="https://www.php.net/">PHP</a></li>
        <li><a target="_blank" href="https://www.postgresql.org/">PostgreSQL</a></li>
    </ul>

    <h2 id="thanks">Special Thanks</h2>

    <p>
        I'd like to thank the <a target="_blank" href="https://minecraft.wiki/">Minecraft Wiki</a> for letting me
        use their API to automatically update the mobs in the vote. The pictures for the mobs are also
        provided by them.
    </p>
    <p>
        Also, I'm awful at design. So, a big thanks goes to the web designer who helped me! (They would like
        to stay anonymous. Apparently, the design is not good enough. <i class="emoji grinning-face-with-smiling-eyes"></i>)
    </p>

</div>