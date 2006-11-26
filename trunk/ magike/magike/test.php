<?php
$xml = "<?xml version=\"1.0\"?>
<methodCall>
<methodName>blogger.setTemplate</methodName>
<params>
<param><value><string>C6CE3FFB3174106584CBB250C0B0519BF4E294</string></value></param>
<param><value><string>744154</string></value></param>
<param><value><string>ewilliams</string></value></param>
<param><value><string>secret</string></value></param>
<param><value><string>&lt;html&gt;&lt;head&gt;&lt;title&gt;&lt;$BlogTitle$&gt;&lt;/title&gt;&lt;/head&gt;&lt;body&gt;&lt;Blogger&gt;&lt;BlogDateHeader&gt;&lt;h1&gt;&lt;$BlogDateHeaderDate$&gt;&lt;/h1&gt;&lt;/BlogDateHeader&gt;&lt;$BlogItemBody$&gt;&lt;br&gt;&lt;/Blogger&gt;&lt;/body&gt;&lt;/html&gt;</string></value></param>
<param><value><string>main</string></value></param>
</params>
</methodCall>";

print_r(xmlrpc_decode_request($xml,$method));
?>