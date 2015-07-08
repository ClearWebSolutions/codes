define(["./../../app"], function(app){
	return {
		init: function(){
				$(".form .btnGrey").click(function(){
					var tables = $("table.form");
					var table = tables[0];
					var index = 	$(table).find('tr').length+1;
					var row = "<tr><td>Language "+index+":</td><td><input type=\"text\" name=\"language"+index+"id\" class=\"languageid\" placeholder=\"id\"/></td><td><input type=\"text\" name=\"language"+index+"name\" placeholder=\"title\"/></td></tr>";
					$(table).append(row);
					app.rerender();
				});
			}
	}
});