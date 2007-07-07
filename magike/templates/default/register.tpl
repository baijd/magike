<[include:header]>

<[module:register]>
<[module:smtp_mailer?waitting_for=register]>
<[module:http_location]>
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
<div id="content">
	<div id="side">
	<div id="incontent">
	<div id="sidecontent">
	<h1>{<a href="{$static_var.siteurl}">{$static_var.blog_name}</a>}</h1>
		<[if:$static_var.user_allow_register]>
		<div class="entry" style="border:none">
			<h2>注册用户</h2>
			<div class="entry_content">
		<div class="comment-form">
		<form method="post" id="register">
			<p class="comment-word">Name (Require):</p>
			<p><input type="text" class="text validate-me" size=30 name="user_name"/> <span class="validate-word" id="user_name-word"></span></p>
			<p class="comment-word">E-mail (Require):</p>
			<p><input type="text" class="text validate-me" size=30 name="user_mail"/> <span class="validate-word" id="user_mail-word"></span></p>
			<p class="comment-word">Homepage:</p>
			<p><input type="text" class="text validate-me" size=30 name="user_url"/> <span class="validate-word" id="user_url-word"></span></p>
			<p class="comment-word">Password (Require):</p>
			<p><input type="password" class="text validate-me" size=30 name="user_password"/> <span class="validate-word" id="user_password-word"></span></p>
			<p class="comment-word">Password Again (Require):</p>
			<p><input type="password" class="text validate-me" size=30 name="user_password_confrm"/> <span class="validate-word" id="user_password_confrm-word"></span></p>
			<p class="comment-word">Describe:</p>
			<p><textarea class="text validate-me" rows=7 cols=50 name="user_about"></textarea></p>
			<p class="comment-word">
			<input type="button" class="button" onclick="magikeValidator('{$static_var.index}/helper/validator/','register');" value="Finish!"/>
			<input type="hidden" name="do" value="register"/>
			</p>
		</form>
		<script>
			function validateSuccess()
			{
				document.getElementById('register').submit();
			}
			$('.validate-word').hide();
		</script>
		</div>
		</div>
		</div>
		<[/if]>
		<[if:!$static_var.user_allow_register]>
		<div class="entry" style="border:none">
			<h2>对不起,用户注册已经关闭</h2>
		</div>
		<[/if]>
	</div>
	<[include:sidebar]>
	</div>
	</div>
</div>
<[include:footer]>
