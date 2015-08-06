
# Overseer

[![Dependency Status](https://www.versioneye.com/user/projects/55c306ab6537620017002b5e/badge.svg?style=flat)](https://www.versioneye.com/user/projects/55c306ab6537620017002b5e)

Overseer is simple-to-use tool for watching files and sending new content in them via e-mail messages.

## Requirements

- PHP 5.3
- enabled PHP function `passthru`
- CLI commands `diff` and `grep`

## Usage

1. Clone repository: `git clone git@bitbucket.org:edgedesigncz/overseer.git`
2. Copy default configuration: `cp config/config.default.ini config/config.ini`
3. Configure overseer: `nano config/config.ini`
4. Install: `composer install --no-dev`
5. Add cron task:

```
*/5    *       *       *       *       php -f /home/myself/overseer/overseer.php 1> /dev/null
```