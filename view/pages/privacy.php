<h1>Privacy Notice</h1>

<div class="text-container">

    <h2>General</h2>

    <p>
        We generally try to be as privacy aware as possible. Our system is build in a way that minimizes the amount
        of personal data needed. As a rule of thumb we use psydonymization if possible. The complete source code is
        available for auditing on <a href="https://github.com/overflowerror/mobmash.click">Github</a>.
    </p>
    <p>
        All data collection and processing is done in accordance with relevant regulations, particularly the GDPR
        (General Data Protection Regulation - (EU) 2016/679). We will never share any personal information with
        3rd parties.
    </p>

    <h2>Data We Collect</h2>

    <h3>Votes (Session IDs)</h3>

    <p data-hx-boost="true">
        In order to be able to provide the websites functionality, we store which mob was chosen by the user, in
        combination with the timestamp and the <a href="#cookies">session</a> IDs. The reason we store the raw data
        instead of aggregated data is that we want to be able to remove votes in case we determine that they are
        spam. The details of the implementation are explained on the <a href="/about#implementation">About</a> page.
    </p>
    <p>
        The data processing is necessary to provide the basic functionality of this website.
    </p>
    <p>
        The association between votes and sessions is deleted after 6 months.
    </p>

    <h3>Audit Log (Session IDs)</h3>

    <p>
        Actions on the website that are relevant for determining whether votes are spam (e.g. when a new session
        was created, when a vote was cast, ...) are logged. The log entries contain the
        <a href="#cookies">session</a> IDs, the timestamp, the event type and some details about the event (e.g.
        the ID of the vote).
    </p>
    <p>
        Processing of security relevant data is a legitimate interest.
    </p>
    <p>
        Audit logs are automatically deleted after 6 months.
    </p>

    <h3>Access Log (IP Addresses, User Agent)</h3>

    <p>
        For security purposes (e.g. fail2ban) we temporarily store the client IP address and user agent string.
        The IP addresses are stored in anonymized form.
    </p>
    <p>
        Processing of security relevant data is a legitimate interest.
    </p>
    <p>
        Access logs are automatically deleted after 6 months.
    </p>

    <h2>Hosting</h2>

    <p>
        This website is hosted in Germany. No data is stored outside the EU.
    </p>

    <h2 id="cookies">Cookies</h2>

    <p>
        We use session cookies. There are multiple reasons for that:
    </p>
    <ul>
        <li>
            Functional: The votes are associated with the session ID. This way we can provide a better experience for
            the users. For example, as long as the session is active, the user will not be shown the same pairing twice.
        </li>
        <li>
            Web Security: We store security-related data in the session that we use to prevent CSRF and
            similar attacks on our website.
        </li>
        <li>
            Spam Protection: We use the session ID in our audit log, so we can find and undo votes by spammers or bots.
        </li>
    </ul>

    <p>
        We do not use 3rd party tracking cookies or advertising cookies of any kind.
    </p>

    <h2>Your Rights</h2>

    <p>
        The GDPR grants you the right to access, rectify, erase or transfer your data, as well as restrict and
        object to processing of your data.
    </p>
    <p>
        However, due to the fact that we only ever store psydonymized data, we are generally not able relate
        specific data sets to a user. In any case, if you do want to exercise your rights, please contact us
        so we can take a look at your specific case.
    </p>

    <h2>Responsibility</h2>

    <p>
        In case you have any question regarding our privacy notice, please reach out via the following email address:
    </p>
    <p>
        Data Controller: <?= PRIVACY_CONTACT ?> (<a href="mailto:<?= PRIVACY_CONTACT_EMAIL ?>"><?= PRIVACY_CONTACT_EMAIL ?></a>)
    </p>
</div>