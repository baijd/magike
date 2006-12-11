[include:header]
[module:archives]
[while@($archives,$archive)]
{$archive.title}
[while@($archive.tags,$tags)]
{$tags}
[/while]

[/while]