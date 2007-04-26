// tinymce plugin for magike
//copy right magike.net

/* Import plugin specific language pack */
tinyMCE.importPluginLanguagePack('magike', 'en,zh_cn_utf8');

var TinyMCE_MagikePlugin = {
	getInfo : function() {
		return {
			longname : 'Magike Plugin',
			author : 'Magike.Net',
			authorurl : 'http://www.magike.net',
			infourl : 'http://www.magike.net',
			version : tinyMCE.majorVersion + "." + tinyMCE.minorVersion
		};
	},
		
	getControlHTML : function(control_name) {
		switch (control_name) {
		case "magike_more":
			return tinyMCE.getButtonHTML(control_name, 'lang_magike_more', '{$pluginurl}/images/more.gif', 'mceMagikeMore');
		case "magike_page":
			return tinyMCE.getButtonHTML(control_name, 'lang_magike_page', '{$pluginurl}/images/page.gif', 'mceMagikePage');
		};
		return "";
	},
		
	execCommand : function(editor_id, element, command, user_interface, value){
		function Magike_Add(str)
		{
				tinyMCE.execCommand("mceInsertContent",true,TinyMCE_MagikePlugin_Loadhtml(str));
				tinyMCE.selectedInstance.repaint();
		}
		
		switch (command) {
				case "mceMagikeMore":
				Magike_Add('more');
				return true;
				case "mceMagikePage":
				Magike_Add('page');
				return true;
		}
		return false;
	},
		
	initInstance: function(inst){
		tinyMCE.importCSS(inst.getDoc(), tinyMCE.baseURL + "/plugins/magike/style.css");		
	},
	
	cleanup : function(type, content, inst) {
		switch (type)
		{
		case "insert_to_editor":
			content = content.replace(new RegExp('<!--more-->','mg'),TinyMCE_MagikePlugin_Loadhtml('more'));
			content = content.replace(new RegExp('<!--page-->','mg'),TinyMCE_MagikePlugin_Loadhtml('page'));		
			break;			
			
		case "get_from_editor":
			content = content.replace(new RegExp('<img(([^>]*)?)class="mce_plugin_magike_more"(([^>]*)?)/>','mg'),'<!--more-->');
			content = content.replace(new RegExp('<img(([^>]*)?)class="mce_plugin_magike_page"(([^>]*)?)/>','mg'),'<!--page-->');
			content = content.replace(new RegExp('<!--more--><!--more-->','mg'),'<!--more-->');
			content = content.replace(new RegExp('<!--page--><!--page-->','mg'),'<!--page-->');
			content = content.replace(new RegExp('<p>([^(</p>)].+?)<!--more-->([^(<p>)].+?)</p>','mg'),'<p>$1</p><!--more--><p>$2</p>');
			content = content.replace(new RegExp('<p>([^(</p>)].+?)<!--page-->([^(<p>)].+?)</p>','mg'),'<p>$1</p><!--page--><p>$2</p>');
			content = content.replace(new RegExp('<p></p>','mg'),'');
			break;
		}
		
		return content;
	}
}

function TinyMCE_MagikePlugin_Loadhtml(str)
{
	return 			''
					+ '<img src="' + (tinyMCE.getParam("theme_href") + "/images/spacer.gif") + '" '
					+ ' width="100%" height="10px" '
					+ 'alt="'+str+'" title="'+str+'" class="mce_plugin_magike_'+str+'" />';
}

tinyMCE.addPlugin("magike", TinyMCE_MagikePlugin);