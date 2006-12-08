[include:header]
[module:archives]
[while@($data.archives,$archives)]
{$archives.title}
[while@($archives.tags,$tags)]
{$tags}
[/while]

[/while]