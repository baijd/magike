<[php]>
echo '<?xml version="1.0" encoding="'.$data["static_var"]["charset"].'"?>';
<[/php]><[module:comment_filter?type=ping]><[module:trackback_insert]><[module:smtp_mailer?waitting_for=trackback_insert]><[module:http_header?content_type=text/xml]>
<response>
<error>{$trackback_insert.success}</error>
<message>{$trackback_insert.word}</message>
</response>
