/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.colordialog =
{
	requires : [ 'dialog' ],

	init : function( editor )
	{
		editor.addCommand( 'colordialog', new CKEDITOR.dialogCommand( 'colordialog' ) );
		CKEDITOR.dialog.add( 'colordialog', this.path + 'dialogs/colordialog.js' );

		// Load YUI js files.
		CKEDITOR.scriptLoader.load( CKEDITOR.getUrl(
			'_source/' + // @Packager.RemoveLine
			'plugins/colordialog/yui/yui.js'
		));

		// Load YUI css files.
		editor.element.getDocument().appendStyleSheet( CKEDITOR.getUrl(
				'_source/' + // @Packager.RemoveLine
				'plugins/colordialog/yui/assets/yui.css'
		));

	}
};

CKEDITOR.plugins.add( 'colordialog', CKEDITOR.plugins.colordialog );
