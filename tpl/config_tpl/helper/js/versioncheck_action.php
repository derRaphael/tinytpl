// VERSIONCHECK
var tinyCoreVersion = {
    major: <?=preg_replace('_\..*_','',self::VERSION)?>,
    minor: <?=preg_replace('_^.*?\.|\..*_','',self::VERSION)?>,
    sub: <?=preg_replace('_^.*\._','',self::VERSION)?>
}

