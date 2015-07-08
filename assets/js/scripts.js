//the list are the required scripts/components to load before launchin the function
define(["app"], function(app) {
			$(document).ready(function(){
				app.init();
				$(window).resize(function(){
					app.rerender();
				});
			});
});


String.prototype.NormalizeVar = function () {
    var str = this;
    var preserveNormalForm = /[,_`;\':-]+/gi
    str = str.replace(preserveNormalForm, ' ');

    // strip accents
    str = stripVowelAccent(str);

    //remove all special chars
    str = str.replace(/[^a-z|^0-9|^-|\s]/gi, '').trim();

    //replace spaces with a -
    str = str.replace(/\s+/gi, '_');
    str = str.replace(/^[0-9]+/,'');
    return str;
}

//String.prototype.trim = function () {
//    return this.replace(/^\s+|\s+$/g, "");
//}

function stripVowelAccent(str) {
    var rExps = [{ re: /[\xC0-\xC6]/g, ch: 'A' },
                 { re: /[\xE0-\xE6]/g, ch: 'a' },
                 { re: /[\xC8-\xCB]/g, ch: 'E' },
                 { re: /[\xE8-\xEB]/g, ch: 'e' },
                 { re: /[\xCC-\xCF]/g, ch: 'I' },
                 { re: /[\xEC-\xEF]/g, ch: 'i' },
                 { re: /[\xD2-\xD6]/g, ch: 'O' },
                 { re: /[\xF2-\xF6]/g, ch: 'o' },
                 { re: /[\xD9-\xDC]/g, ch: 'U' },
                 { re: /[\xF9-\xFC]/g, ch: 'u' },
                 { re: /[\xD1]/g, ch: 'N' },
                 { re: /[\xF1]/g, ch: 'n'}];

    for (var i = 0, len = rExps.length; i < len; i++)
        str = str.replace(rExps[i].re, rExps[i].ch);

    return str;
}