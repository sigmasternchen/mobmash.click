## 0.7.0 (2024-08-08)

### Feat

- Add dynamic favicons
- Add padding between mob image and name in results page
- Change hamburger menu to close button when menu open on mobile

### Fix

- Undefined function

## 0.6.0 (2024-08-08)

### Feat

- Add SEO info

## 0.5.1 (2024-08-07)

### Fix

- Deprecation warning for PHP 8.2

## 0.5.0 (2024-08-07)

### Feat

- Update About page + README
- Add emoji
- Add robots.txt

### Fix

- Trends now not only depend on the history cache but also the current history
- Open Github link in new tab

## 0.4.1 (2024-08-06)

### Fix

- About links covering menu

## 0.4.0 (2024-08-06)

### Feat

- Add statistics to about page

### Fix

- Make everything smaller so mobile safari menu doesn't cover mobs
- Potentially fix for color swap in telegram browser

## 0.3.1 (2024-08-06)

### Fix

- Trailing slashes break the menu

## 0.3.0 (2024-08-06)

### Feat

- Add mobile version of results page
- Add mobile version of homepage + menu

## 0.2.10 (2024-08-05)

### Fix

- Error in rating calculation

## 0.2.9 (2024-08-05)

### Fix

- Do not delete mob images when deploying

## 0.2.8 (2024-08-05)

### Fix

- Replacement in wrong file in deploy workflow

## 0.2.7 (2024-08-05)

### Fix

- Initial pairing doesn't have csrf token yet

## 0.2.6 (2024-08-05)

### Fix

- Typo in command

## 0.2.5 (2024-08-05)

### Fix

- Remove resources folder before mirroring in deploy workflow + add debug output

## 0.2.4 (2024-08-05)

### Fix

- Missing delimiter in regex in deploy workflow

## 0.2.3 (2024-08-05)

### Fix

- Missing quote in deploy workflow

## 0.2.2 (2024-08-05)

### Fix

- Wrong sed syntax in deploy workflow

## 0.2.1 (2024-08-05)

### Fix

- We don't use a prefix in the version tag

## 0.2.0 (2024-08-05)

### Feat

- Add housekeeping bin + slight restructure of cron jobs

### Fix

- Add missing configuration to config file

## 0.1.0 (2024-08-04)

### Feat

- Add version number to layout
- Add links to Minecraft Wiki on results page
- Add disclaimer to footer
- Add contact section to about page
- Add privacy notice page
- Add New Pairing button
- Add audit log
- A bit of styling for links + add thanks block for web design
- Add about page
- Add page indicator to navbar
- First draft of navbar + changes to sizing
- Add sorting options for results table
- A bit of styling for results table
- Add trend graphs to result page
- Basic results page
- Add history caching
- Use minecraft font
- Change loading animation
- Add no-js fallback
- AJAX loading with loading animation
- Add htmx dependency
- Add CSRF protection
- Add voting logic
- Redo of rating view
- Basics for more advanced pairing
- Add session to match view
- Add custom user agent for API requests
- Add commitizen and version file
- First draft of layout
- Show random mobs in UI
- Basic migration system + initial migration
- Add new mobs to database
- Add image download logic

### Fix

- direction indicators were the wrong way round
- Left side stopped working during previous fix
- expectation calculation was wrong
- winner column was read 1-indexed but written 0-indexed
- img-preload doesn't support data prefix + running cursor fix on load doesn't work
- Use standard html attributes
- Loading animation issues
