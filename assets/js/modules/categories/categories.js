define([], function(){
	return {
		init: function(){
			this.titleListener();
		},

		titleListener: function(){
			$("input[name='title']").keyup(function(){
				var val = $(this).val().NormalizeVar().toLowerCase();
				$("input[name='db_tbl']").val(val);
			});
			$("input[name='title']").change(function(){
				var val = $(this).val().NormalizeVar().toLowerCase();
				$("input[name='db_tbl']").val(val);
			});
		}

	}
});