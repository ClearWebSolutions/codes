define(["pages", "modules", "settings"], function(pages, modules, settings){

	return{

		init: function(){
			pages.init();
			modules.init();
			settings.init();
			this.rerender();
		},

		rerender: function(){
			pages.rerender();
			modules.rerender();
		}

	};
});