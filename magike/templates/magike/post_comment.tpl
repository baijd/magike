<[module:comment_filter?key=do&val=insert&type=comment]>
<[module:comment_insert]>
<[module:smtp_mailer?waitting_for=comment_insert]>
<[if:$comment_insert.open]>{$comment_insert.word}<[/if]>
