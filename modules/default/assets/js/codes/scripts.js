$(document).ready(function(){
	if($("a.fancybox").length>0){
		$("a.fancybox").fancybox();
	}

	$(".language_switcher").click(function(){
		//modify href
		var right = location.href.split(siteurl);
		right = right[1];
		//checking the right side for 2 letter language code
		var parts = right.split("/");
		if(parts[0].length==2){right=right.substr(3);}
		var newurl = siteurl+$(this).attr('lid')+"/"+right;
		//reload page
		location.href = newurl;
	});
});