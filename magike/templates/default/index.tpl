[module:archives]
[module:static]
[while@($archives,$i)]
{$static.siteurl}dd{$archives[$i].title}
{$lang.public.write_by}
[/while]