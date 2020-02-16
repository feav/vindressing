(function ( $ ) {
	"use strict";
	$.JmsMegamenu   = $.JmsMegamenu || {};
	$.JmsMegamenu['data']    = {};
	$.JmsMegamenu['allow_submit'] = false;

	// Add button show modal setting
	function add_setting_button( pending ){
		var class_pending = pending ? '.pending' : '';

		$( '#menu-to-edit > .menu-item' + class_pending ).each( function(){
			var _this = $(this);
			if( _this.find( '.item-title .jms-setting-btn' ).length ) {
				return;
			}
			// Get id menu item
			var id = parseInt( _this.attr('id').split( 'menu-item-' )[1] );
			// Get level menu item
			var level = parseInt( _this.attr( 'class' ).split( 'menu-item-depth-' )[1].split( ' ' )[0] );
			// Render button
			_this.find( '.item-title' ).append( '<span class="jms-setting-btn button-primary">Settings</span>' );
			// Creat data
			$.JmsMegamenu['data'][id] = get_item_data( id, level );

			// Add active icon
			if( level == 0 && $.JmsMegamenu['data'][id]['mega'] == 1 ) {
				_this.addClass( 'megamenu-active' );
			}

		});
	}

	function button_expand(){
        var list_menu_item = $( '#menu-to-edit > .menu-item' );
		var has_expand     = false;

		$.each( list_menu_item, function( key, val ){
			var _this      = $(this);
			var level      = parseInt( _this.attr( 'class' ).split( 'menu-item-depth-' )[1].split( ' ' )[0] );
			var el_next    = $( list_menu_item[ key + 1 ] );
			var level_next = level + 1;

			if( el_next.hasClass( 'menu-item-depth-' + level_next ) ) {
				if( ! _this.find( '.menu-item-bar .wr-expand' ).length ) {
					_this.find( '.menu-item-bar' ).append( '<span class="button-expand"></span>' );
				}
			} else {
				_this.find( '.menu-item-bar .wr-expand' ).remove();
			}
		});
	}

    // Add expand all and collapse all
	function button_expand_collapse_all(){

		var dom = '<ul class="expand-collapse"><li class="expand-all">Expand all</li><li class="collapse-all">Collapse all</li></ul>';
		$( dom ).insertBefore( '#menu-to-edit' );


		$( 'body' ).on( 'click', '.expand-collapse .expand-all', function(){
			$( '#menu-to-edit > .menu-item:not(".menu-item-depth-0")' ).show();
			$( '#menu-to-edit > .menu-item .button-expand.collapse' ).removeClass( 'collapse' );
		} );

		$( 'body' ).on( 'click', '.expand-collapse .collapse-all', function(){
			$( '#menu-to-edit > .menu-item:not(".menu-item-depth-0")' ).hide();
			$( '#menu-to-edit > .menu-item .button-expand' ).addClass( 'collapse' );
		} );

		/*===*===*===*===*===*===*===*===*===*     Expand     *===*===*===*===*===*===*===*===*===*/
		$( '#menu-to-edit' ).on( 'click', '.button-expand', function() {
			var _this = $(this);
			var hide_flag  = true;

			if( _this.hasClass( 'collapse' ) ) {
				_this.removeClass( 'collapse' );
				hide_flag = false;
			} else {
				_this.addClass( 'collapse' );
			}

			var parent         = _this.closest( '.menu-item' );
			var level          = parseInt( parent.attr( 'class' ).split( 'menu-item-depth-' )[1].split(' ')[0] );
			var list_menu_item = $( '#menu-to-edit .menu-item' );
			var index_current  = list_menu_item.index( parent );

			$.each( list_menu_item, function( key, val ){
				if( key > index_current ) {
					var _this          = $(this);
					var level_children = parseInt( _this.attr( 'class' ).split( 'menu-item-depth-' )[1].split(' ')[0] );

					if( level_children <= level ) {
						return false;
					} else {
						if( hide_flag ) {
							_this.hide();
						} else {
							_this.show();

							_this.find( '.button-expand.collapse' ).removeClass( 'collapse' );
						}
					}
				}

			} );
		});

		// Delete menu parent
		$( '#menu-to-edit' ).on( 'click', '.item-delete', function(){
			var _this          = $(this);
			var parent         = _this.closest( '.menu-item' );
			var level          = parseInt( parent.attr( 'class' ).split( 'menu-item-depth-' )[1].split(' ')[0] );
			var list_menu_item = $( '#menu-to-edit .menu-item' );
			var index_current  = list_menu_item.index( parent );

			$.each( list_menu_item, function( key, val ){
				if( key > index_current ) {
					var _this          = $(this);
					var level_children = parseInt( _this.attr( 'class' ).split( 'menu-item-depth-' )[1].split(' ')[0] );

					if( level_children <= level ) {
						return false;
					} else {
						_this.show();
					}
				}
			} );
		} );
	}

	function get_item_data( id, level ){
		var level_default = ( level > 1 ) ? 2 : level;
		level_default++;

		var data = {};
		if( $.JmsMegamenu['data'][id] != undefined ) {
			data = $.extend( {}, jmsmegamenu_data_default[ 'lvl_'+ level_default ], $.JmsMegamenu['data'][id] );
		} else if( jms_data_megamenu[id] != undefined ){
			data = $.extend( {}, jmsmegamenu_data_default[ 'lvl_'+ level_default ], jms_data_megamenu[id] );
		} else {
			data = jmsmegamenu_data_default[ 'lvl_'+ level_default ];
		}

		data['level'] = level;

		return data;
	}
	function update_item_data( element, val, key ){
		if( key ) {
			var id = element.closest( '.jms-wrapper' ).attr( 'data-id' );
    		$.JmsMegamenu.data[id][key] = val;
		}
	}
	function load_content_element( type, content ) {
		var html_render = '';

		switch( type ){
			case 'html':
				var wp_editor = $( 'script#jms-html-element' ).html();
				wp_editor = wp_editor.replace( '_WR_CONTENT_', content );
				$( '.jms-wrapper .element-content' ).html( wp_editor );
				var render_editor = function(){
					var intTimeout = 5000;
			        var intAmount  = 100;
			        var iframe_load_completed = true;
			        var ifLoadedInt = setInterval(function(){
			            if (iframe_load_completed || intAmount >= intTimeout) {
			                ( function() {
			                    var init, id, $wrap;
			                    // Render Visual Tab
			                    for ( id in tinyMCEPreInit.mceInit ) {
			                        if ( id != 'jms-editor' )
			                            continue;
			                        init  = tinyMCEPreInit.mceInit[id];
			                        $wrap = tinymce.$( '#wp-' + id + '-wrap' );
			                        tinymce.remove(tinymce.get('jms-editor'));
			                        tinymce.init( init );
			                        setTimeout( function(){
			                            $( '#wp-jms-editor-wrap' ).removeClass( 'html-active' );
			                            $( '#wp-jms-editor-wrap' ).addClass( 'tmce-active' );
			                        }, 10 );
			                        if ( ! window.wpActiveEditor )
			                                window.wpActiveEditor = id;
			                        break;
			                    }
			                    // Render Text tab
			                    for ( id in tinyMCEPreInit.qtInit ) {
			                        if ( id != 'jms-editor' )
			                            continue;
			                        quicktags( tinyMCEPreInit.qtInit[id] );
			                        // Re call inset quicktags button
			                        QTags._buttonsInit();
			                        if ( ! window.wpActiveEditor )
			                            window.wpActiveEditor = id;
			                        break;
			                    }
			                }());
			                iframe_load_completed = false;
			                window.clearInterval(ifLoadedInt);
			            }
			        },
			        intAmount
			        );
				};
				render_editor();
				break;
			case '':
				$( '.jms-wrapper .element-content' ).html( '' );
				break;
		}

		return html_render;
	}
	function save_ajax(){
		var data_options = {};
		data_options['event'] = $('#megamenu-event').val();
		data_options['type'] = $('#megamenu-type').val();
		$.ajax( {
			type   : "POST",
			url    : jms_megamenu.ajaxurl,
			data   : {
				action           : 'jms_save_options',
				_nonce           : jms_megamenu._nonce,
				menu_id          : jms_megamenu.menu_id,
				data             : data_options,
				data_last_update : 'ok'
			},
			success: function ( data_return ) {

			}
		});
		// Remove data null before udpate
		var data_save = {};
		$.each( $.JmsMegamenu.data, function( key, val ){
			data_save[key] = {};

			$.each( val, function( key_item, val_item ) {

				if( typeof val_item == 'string' )
					val_item = val_item.trim();
				if( val_item !== '' ) {
					switch( val['level'] ) {
					    case 0:
					    		data_save[key][key_item] = val_item;
					        break;
					    case 1:
					    		data_save[key][key_item] = val_item;
					        break;
					    default:
					    		data_save[key][key_item] = val_item;
					}
				}

			});
		});
		$.ajax( {
			type   : "POST",
			url    : jms_megamenu.ajaxurl,
			data   : {
				action           : 'jms_save_megamenu',
				_nonce           : jms_megamenu._nonce,
				menu_id          : jms_megamenu.menu_id,
				data             : data_save,
				data_last_update : 'ok',
			},
			success: function ( data_return ) {
				// Parse data
				var data_return = ( data_return ) ? JSON.parse( data_return ) : '';
				if( data_return.status == 'true' ) {
					if( $( '.jms-error' ).length ) {
						$( '.jms-error' ).remove();
					}
					$.JmsMegamenu.allow_submit = true;
					// Submit form
					$( '.wp-admin #update-nav-menu' ).submit();
				} else if( data_return.status == 'updating' ) {
					$.each( data_return.list_id_updated , function ( value, key ) {
						delete $.JmsMegamenu.data[ key ];
					});

					// Update next data
					$.JmsMegamenu.save_ajax();

				} else if( data_return.status == 'false' ) {
					if( $( '.jms-loading' ).length ) {
						$( '.jms-loading' ).remove();
					}

					// Show error
					$( '.major-publishing-actions .publishing-action' ).prepend( '<p class="jms-error">' + data_return.message + '</p>' );
				}
			}
		});

	}
	function get_childs(id) {
		var item_parent = $(this).parent();
		var add_allow = false;
		var childs = [];
		var _index = 0;
		$('#menu-to-edit .menu-item').each( function(){
			var _this = $(this);
			var menu_id = parseInt( _this.attr('id').split( 'menu-item-' )[1] );
			var level = parseInt( _this.attr( 'class' ).split( 'menu-item-depth-' )[1].split( ' ' )[0] );
			if(add_allow && (level == 0)) add_allow = false;
			if(add_allow && (level == 1)) {
				childs[_index] =  $( '#menu-item-' + menu_id + ' .menu-item-title' ).text();
				_index++;
			}
			if(menu_id == id) add_allow = true;
		});
		return childs;
	}
	function get_layout_str() {
		var layout_str = '';
		var cols = [];
		$('.mega-column').each( function(index){
			cols[index] = $(this).attr('data-col');
		});
		layout_str = cols.join('-');
		return layout_str;
	}
	function event_listen(){
		$( '#menu-to-edit' ).on( 'click', '.jms-setting-btn', function(){
			var _this       = $(this);
			var item_parent = _this.closest( '.menu-item' );

			// Get id menu item
			var id = parseInt( item_parent.attr('id').split( 'menu-item-' )[1] );

			// Get level menu item
			var level = parseInt( item_parent.attr( 'class' ).split( 'menu-item-depth-' )[1].split( ' ' )[0] );

			$.JmsMegamenu['data'][id] = get_item_data( id, level );

			var mega_active        = 0;
			var mega_type        = '';
			var total_menu_item_lv_2 = 0;

			if( level == 0 ) {
				mega_active = parseInt( $.JmsMegamenu['data'][id]['mega'] );
				mega_type = parseInt( $.JmsMegamenu['data'][id]['mega_type'] );
			} else if( level == 1 ) {
				var parent_lv_0 = item_parent.prevAll( '.menu-item-depth-0:first' );
				var parent_id   = parseInt( parent_lv_0.attr('id').split( 'menu-item-' )[1] );

				mega_active   = ( $.JmsMegamenu['data'][parent_id] != undefined ) ? $.JmsMegamenu['data'][parent_id]['mega'] : 0;
			} else if( level == 2 ) {
				var parent_lv_0 = item_parent.prevAll( '.menu-item-depth-0:first' );
				var grandparent_id   = parseInt( parent_lv_0.attr('id').split( 'menu-item-' )[1] );
				mega_active   = ( $.JmsMegamenu['data'][grandparent_id] != undefined ) ? $.JmsMegamenu['data'][grandparent_id]['mega'] : 0;
			}

			/* Check level 0 has menu children */
			var has_children = false;
			var cols = [];
			if( level == 0 && item_parent.next( '.menu-item-depth-1' ).length != 0 ){
				has_children = true;
				cols = $.JmsMegamenu['data'][id]['submenu_layout'].split('-');
			}
			/* Check level 1 has menu children */
			if( level == 1 && item_parent.next( '.menu-item-depth-2' ).length != 0 && mega_active == 0){
				has_children = true;
			}
			/* Check level 2 has menu children */
			if( level == 2 && item_parent.next( '.menu-item-depth-3' ).length != 0 && mega_active == 1){
				has_children = true;
			}
			var childs = get_childs(id);

			// Get html
			var template_show = _.template( $( "script#jms-template" ).html() )({
				data_item         : $.JmsMegamenu['data'][id],
				title_modal       : item_parent.find( '.menu-item-title' ).text(),
				level             : level,
				mega_active 	  : mega_active,
				mega_type		  : mega_type,
				id                : id,
				"$"               : jQuery,
				has_children      : has_children,
				childs			  : childs,
				cols			  : cols
			});
			$( 'body' ).append( $( 'script#jms-modal-html' ).html() );
			$( '.jms-dialog' ).html( template_show );
			$( '.jms-modal' ).addClass( 'main-settings' );

			// Load content element for menu item level 2
			if( level == 1 ) {
				var _content = '';
				if($.JmsMegamenu['data'][id]['element_type'] == 'html')
					_content = $.JmsMegamenu['data'][id]['html_data'];
				load_content_element( $.JmsMegamenu['data'][id]['element_type'], _content );
			}

			/* Action for modal */
			var modal         = $( '.jms-dialog' );
			var modal_info    = modal[0].getBoundingClientRect();
			var window_el     = $(window);
			var scroll_top    = window_el.scrollTop();
			var height_window = window_el.height();
			var top_position  = 0;

			if( modal_info.height < height_window ) {
				top_position = scroll_top + ( ( height_window - modal_info.height ) / 2 );
			} else {
				top_position = scroll_top + 10;
			}
			modal.css( 'top', top_position );

		})
		// Close popup
		$('body').on('click', '.dialog-title .close', function() {
			$(this).closest( '.jms-modal' ).remove();

			$( '.jms-modal.main-settings.hidden' ).removeClass( 'hidden' );
		});
		// enable megamenu
		$('body').on('click', '.jms-wrapper .mega-enable', function() {
			var _this = $( this );
			//Get id current
			var parent_current 	= _this.closest( '.jms-wrapper' );

			var id_el = $( '#menu-item-' + parent_current.attr( 'data-id' ) ) ;

			var menu_item 		= $( '#menu-to-edit li.menu-item' );
			if($(this).is(":checked")) {
				var value = '1';
				parent_current.find( '.mega-options' ).stop( true, false ).slideDown();

				// Add active
				id_el.addClass( 'megamenu-active' );
			} else {
				var value = '0';
				parent_current.find( '.mega-options' ).stop( true, false ).slideUp();

				// Remove active
				id_el.removeClass( 'megamenu-active' );
			}
			update_item_data( _this, value, 'mega');
		});
		$('body').on('change', '.jms-wrapper .mega-type', function() {
			var _this = $( this );
			var value = _this.val();
			//Get id current
			var parent_current 	= _this.closest( '.jms-wrapper' );

			var id_el = $( '#menu-item-' + parent_current.attr( 'data-id' ) ) ;
			if(value != '') {
				parent_current.find( '.mega-layout' ).stop( true, false ).slideUp();
				// Add active
				id_el.addClass( 'megamenu-active' );
			} else {
				parent_current.find( '.mega-layout' ).stop( true, false ).slideDown();
				// Remove active
				id_el.removeClass( 'megamenu-active' );
			}
			update_item_data( _this, value, 'mega_type');
		});
		// show title
		$('body').on('click', '.jms-wrapper .show-title', function() {
			var _this = $( this );
			if($(this).is(":checked")) {
				var value = '1';
			} else {
				var value = '0';
			}
			update_item_data( _this, value, 'show_title');
		});
		// show Logo
		$('body').on('click', '.jms-wrapper .show-logo', function() {
			var _this = $( this );
			if($(this).is(":checked")) {
				var value = '1';
			} else {
				var value = '0';
			}
			update_item_data( _this, value, 'show_logo');
			if(value == '1')
				update_item_data( _this, '0', 'show_title');
		});
		//width change
		$('body').on('blur', '.jms-wrapper .icon-class', function() {
			var value = $( this ).val();
			update_item_data( $( this ), value, 'icon_class');
		});
		// column heading
		$('body').on('click', '.jms-wrapper .column-heading', function() {
			var _this = $( this );
			if($(this).is(":checked")) {
				var value = '1';
			} else {
				var value = '0';
			}
			update_item_data( _this, value, 'column_heading');
		});
		//width change
		$('body').on('blur', '.jms-wrapper .number-width', function() {
			var value = $( this ).val().replace(/[^0-9]/gi, '');
			update_item_data( $( this ), value, 'width');
		});
		//width type change
		$('body').on('change', '.jms-wrapper .width-type', function() {
			var value = $(this).val();
			update_item_data( $( this ), value, 'width_type');

			if( value == 'fixed' ){
				$( this ).closest( '.mega-option' ).find( '.width-box' ).removeAttr( 'style' );
			} else {
				$( this ).closest( '.mega-option' ).find( '.width-box' ).hide();
			}
		});
		//width change
		$('body').on('blur', '.jms-wrapper .submenu-class', function() {
			var value = $( this ).val();
			update_item_data( $( this ), value, 'submenu_class');
		});

		$('body').on('click', '.jms-wrapper .tool-align', function(e) {
			e.preventDefault();
			var value = $( this ).data('align');
			update_item_data( $( this ), value, 'align');
			$('.tool-align').removeClass('active');
			$( this ).addClass('active');
		});
		$('body').on('click', '.jms-wrapper .red-width', function() {
			var active_column = $(this).closest('.mega-column');
			var old_col = parseInt(active_column.attr('data-col'));
			if(old_col > 1) {
				var new_col = old_col - 1;
				active_column.attr('data-col',new_col);
				active_column.removeClass (function (index, css) {
					return (css.match (/(^|\s)col-\S+/g) || []).join(' ');
				});
				active_column.addClass('col-' +  new_col);
				var layout_str = get_layout_str();
				$('.submenu-layout').val(layout_str);
				update_item_data( $( this ), layout_str, 'submenu_layout');
			}
		});
		$('body').on('click', '.jms-wrapper .inc-width', function() {
			var active_column = $(this).closest('.mega-column');
			var old_col = parseInt(active_column.attr('data-col'));
			if(old_col < 12) {
				var new_col = old_col + 1;
				active_column.attr('data-col',new_col);
				active_column.removeClass (function (index, css) {
					return (css.match (/(^|\s)col-\S+/g) || []).join(' ');
				});
				active_column.addClass('col-' +  new_col);
				var layout_str = get_layout_str();
				$('.submenu-layout').val(layout_str);
				update_item_data( $( this ), layout_str, 'submenu_layout');
			}
		});
		//layout change
		$('body').on('blur', '.jms-wrapper .submenu-layout', function() {
			var value = $( this ).val();
			var cols = value.split('-');
			$(cols).each( function(index, val){
				$('.mega-column').eq(index).removeClass (function (index, css) {
				   return (css.match (/(^|\s)col-\S+/g) || []).join(' ');
				});
				$('.mega-column').eq(index).addClass('col-' +  val);
				$('.mega-column').eq(index).attr('data-col',val);
			});
			update_item_data( $( this ), value, 'submenu_layout');
		});
		//swith content element
		$('body').on('change', '.jms-wrapper .element-type', function() {
			var value = $(this).val();
			var parent_current 	= $(this).closest( '.jms-wrapper' );
			var id = parent_current.attr( 'data-id');
			var _content = '';
			if(value == 'html') {
				if($.JmsMegamenu['data'][id]['html_data'] != null)
					_content = $.JmsMegamenu['data'][id]['html_data'];
			}
			load_content_element( value, _content);
			update_item_data( $( this ), value, 'element_type');
		});
		// Change value text element
		$('body').on('change', '.jms-wrapper .jms-html-element .jms-editor-hidden', function( e ) {
			var _this       = $(this);
			var data_insert = _this.val();
			if($('.jms-wrapper .element-type').val() == 'html')
				update_item_data( _this, data_insert, 'html_data');
		});
		/*===*===*===*===*===*===*===*===*===*     SAVE DATA     *===*===*===*===*===*===*===*===*===*/
		// Save data menu
		$( '.wp-admin #update-nav-menu' ).on( "submit", function( e ) {
			if( Object.keys( $.JmsMegamenu.data ).length ) {
				if( $.JmsMegamenu.allow_submit == false ) {
					e.preventDefault();
					// Save data
					save_ajax();
				}
			}
		});
	}
	add_setting_button( false );
	button_expand();
	button_expand_collapse_all();
	event_listen();
})( jQuery );
