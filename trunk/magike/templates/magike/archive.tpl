<[include:header]>
<[include:menu]>

<[module:post]>
<[module:comments_list?sort=ASC]>
<[if:$static_var.comment_ajax_validator]>
<script language="javascript" type="text/javascript" src="{$static_var.siteurl}/templates/{$static_var.admin_template}/javascript/jquery.js"></script>
<script>
if (!Object.prototype.toJSONString) {
    (function (s) {
        var m = {
            '\b': '\\b',
            '\t': '\\t',
            '\n': '\\n',
            '\f': '\\f',
            '\r': '\\r',
            '"' : '\\"',
            '\\': '\\\\'
        };

        s.parseJSON = function (hook) {
            try {
                if (/^("(\\.|[^"\\\n\r])*?"|[,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t])+?$/.
                        test(this)) {
                    var j = eval('(' + this + ')');
                    if (typeof hook === 'function') {
                        function walk(v) {
                            if (v && typeof v === 'object') {
                                for (var i in v) {
                                    if (v.hasOwnProperty(i)) {
                                        v[i] = walk(v[i]);
                                    }
                                }
                            }
                            return hook(v);
                        }
                        return walk(j);
                    }
                    return j;
                }
            } catch (e) {
            }
            throw new SyntaxError("parseJSON");
        };
    })(String.prototype);
}

function magikeValidator(url,mod)
{
	validateElements = null;
	$(".validate-word").hide();
	
	s = $('.validate-me').serialize();
	$.ajax({
		type: 'POST',
		url: url + '?mod=' + mod,
		data: s,
		success: function(data){
			js = data.parseJSON();
			if(js != 0)
			{
				for(var i in js)
				{
					$("#" + i + "-word").show();
					$("#" + i + "-word").html(js[i]);
				}
			}
			else
			{
				validateSuccess.apply(this);
			}
		}
	});
}
</script>
<[/if]>

<div id="content">
	<div id="side">
	<div id="sidecontent">
		<div class="entry" style="border:none">
			<h2><a href="{$static_var.index}/archives/{$post.post_id}/">{$post.post_title}</a></h2>
			<div class="entry_tags"><strong>Tags:</strong>
			<[if:$post.post_tags]>
			<[loop:$post.post_tags AS $tag]>
			<a href="{$static_var.index}/tags/{$tag}/">{$tag}</a>
			<[/loop]>
			<[/if]>
			<[if:!$post.post_tags]>
			没有
			<[/if]>
			</div>
			<div class="entry_date">{$post.post_time}</div>
			<div class="entry_content">
			{$post.post_content}
			</div>
		</div>
		<h2 class="response">共有{$post.post_comment_num}条评论</h2>
		<ol class="comment" id="comment">
		    <[loop:$comments_list AS $comment]>
		    <li id="comment-{$comment.comment_id}">
		    <cite><[if:$comment.comment_homepage != NULL]><a href="{$comment.comment_homepage}"><[/if]>{$comment.comment_user}<[if:$comment.comment_homepage != NULL]></a><[/if]> says:
			<[if:$comment.comment_publish == "waitting"]>(等待审核)<[/if]></cite><br />
		    <small>{$comment.comment_date}</small>
		    <p>{$comment.comment_text}</p>
		    </li>
		    <[/loop]>
		</ol>
		<div class="comment-form">
		<form method="post" id="post_comment" action="{$static_var.index}/post_comment/{$post.post_id}/?referer={$static_var.index}/archives/{$post.post_id}/">
			<p><input type="text" class="text validate-me" size=30 name="comment_user"/> 名称<[if:$static_var.comment_ajax_validator]><span class="validate-word" id="comment_user-word"></span><[/if]></p>
			<p><input type="text" class="text validate-me" size=30 name="comment_email"/> 电子邮件<[if:$static_var.comment_ajax_validator]><span class="validate-word" id="comment_email-word"></span><[/if]></p>
			<p><input type="text" class="text validate-me" size=30 name="comment_homepage"/> 个人主页<[if:$static_var.comment_ajax_validator]><span class="validate-word" id="comment_homepage-word"></span><[/if]></p>
			<p><textarea class="text validate-me" rows=7 cols=50 name="comment_text"></textarea></p>
			<[if:$static_var.comment_ajax_validator]><p><span class="validate-word" id="comment_text-word"></span></p><[/if]>
			<p>
			<[if:$static_var.comment_ajax_validator]>
			<input type="button" onclick="magikeValidator('{$static_var.index}/helper/validator/','post_comment');" value="提交评论"/>
			<[/if]>
			<[if:!$static_var.comment_ajax_validator]>
			<input type="submit" value="提交评论"/>
			<[/if]>
			<input type="hidden" name="do" value="insert"/></p>
		</form>
		<[if:$static_var.comment_ajax_validator]>
		<script>
			function validateSuccess()
			{
				document.getElementById('post_comment').submit();
			}
			$('.validate-word').hide();
		</script>
		<[/if]>
		</div>
	</div>
	<[include:sidebar]>
	</div>
</div>
<[include:footer]>
