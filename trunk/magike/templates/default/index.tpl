[include:header]
[module:archives]
[module:static]
[while@($archives,$i)]
{$archives[$i].title}

[while@($archives[$i].tags,$j)]
{$archives[$i].tags[$j]}
[/while]

[/while]