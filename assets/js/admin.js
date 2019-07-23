(function(d){d.each(["backgroundColor","borderBottomColor","borderLeftColor","borderRightColor","borderTopColor","color","outlineColor"],function(f,e){d.fx.step[e]=function(g){if(!g.colorInit){g.start=c(g.elem,e);g.end=b(g.end);g.colorInit=true}g.elem.style[e]="rgb("+[Math.max(Math.min(parseInt((g.pos*(g.end[0]-g.start[0]))+g.start[0]),255),0),Math.max(Math.min(parseInt((g.pos*(g.end[1]-g.start[1]))+g.start[1]),255),0),Math.max(Math.min(parseInt((g.pos*(g.end[2]-g.start[2]))+g.start[2]),255),0)].join(",")+")"}});function b(f){var e;if(f&&f.constructor==Array&&f.length==3){return f}if(e=/rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/.exec(f)){return[parseInt(e[1]),parseInt(e[2]),parseInt(e[3])]}if(e=/rgb\(\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*\)/.exec(f)){return[parseFloat(e[1])*2.55,parseFloat(e[2])*2.55,parseFloat(e[3])*2.55]}if(e=/#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/.exec(f)){return[parseInt(e[1],16),parseInt(e[2],16),parseInt(e[3],16)]}if(e=/#([a-fA-F0-9])([a-fA-F0-9])([a-fA-F0-9])/.exec(f)){return[parseInt(e[1]+e[1],16),parseInt(e[2]+e[2],16),parseInt(e[3]+e[3],16)]}if(e=/rgba\(0, 0, 0, 0\)/.exec(f)){return a.transparent}return a[d.trim(f).toLowerCase()]}function c(g,e){var f;do{f=d.css(g,e);if(f!=""&&f!="transparent"||d.nodeName(g,"body")){break}e="backgroundColor"}while(g=g.parentNode);return b(f)}var a={aqua:[0,255,255],azure:[240,255,255],beige:[245,245,220],black:[0,0,0],blue:[0,0,255],brown:[165,42,42],cyan:[0,255,255],darkblue:[0,0,139],darkcyan:[0,139,139],darkgrey:[169,169,169],darkgreen:[0,100,0],darkkhaki:[189,183,107],darkmagenta:[139,0,139],darkolivegreen:[85,107,47],darkorange:[255,140,0],darkorchid:[153,50,204],darkred:[139,0,0],darksalmon:[233,150,122],darkviolet:[148,0,211],fuchsia:[255,0,255],gold:[255,215,0],green:[0,128,0],indigo:[75,0,130],khaki:[240,230,140],lightblue:[173,216,230],lightcyan:[224,255,255],lightgreen:[144,238,144],lightgrey:[211,211,211],lightpink:[255,182,193],lightyellow:[255,255,224],lime:[0,255,0],magenta:[255,0,255],maroon:[128,0,0],navy:[0,0,128],olive:[128,128,0],orange:[255,165,0],pink:[255,192,203],purple:[128,0,128],violet:[128,0,128],red:[255,0,0],silver:[192,192,192],white:[255,255,255],yellow:[255,255,0],transparent:[255,255,255]}})(jQuery);
(function($) {var sR = {defaults: {slideSpeed: 400,easing: false,callback: false },thisCallArgs: {slideSpeed: 400,easing: false,callback: false},methods: {up: function (arg1,arg2,arg3) {if(typeof arg1 == 'object') {for(p in arg1) {sR.thisCallArgs.eval(p) = arg1[p];}}else if(typeof arg1 != 'undefined' && (typeof arg1 == 'number' || arg1 == 'slow' || arg1 == 'fast')) {sR.thisCallArgs.slideSpeed = arg1;}else{sR.thisCallArgs.slideSpeed = sR.defaults.slideSpeed;}if(typeof arg2 == 'string'){sR.thisCallArgs.easing = arg2;}else if(typeof arg2 == 'function'){sR.thisCallArgs.callback = arg2;}else if(typeof arg2 == 'undefined') {sR.thisCallArgs.easing = sR.defaults.easing;}if(typeof arg3 == 'function') {sR.thisCallArgs.callback = arg3;}else if(typeof arg3 == 'undefined' && typeof arg2 != 'function'){sR.thisCallArgs.callback = sR.defaults.callback;}var $cells = $(this).find('td');$cells.wrapInner('<div class="slideRowUp" />');var currentPadding = $cells.css('padding');$cellContentWrappers = $(this).find('.slideRowUp');$cellContentWrappers.slideUp(sR.thisCallArgs.slideSpeed,sR.thisCallArgs.easing).parent().animate({paddingTop: '0px',paddingBottom: '0px'},{complete: function () { $(this).children('.slideRowUp').replaceWith($(this).children('.slideRowUp').contents()); $(this).parent().css({'display':'none'}); $(this).css({'padding': currentPadding});}});var wait = setInterval(function () {if($cellContentWrappers.is(':animated') === false) {clearInterval(wait);if(typeof sR.thisCallArgs.callback == 'function') { sR.thisCallArgs.callback.call(this);}}}, 100);return $(this);},down: function (arg1,arg2,arg3) {if(typeof arg1 == 'object') {for(p in arg1) {sR.thisCallArgs.eval(p) = arg1[p];}}else if(typeof arg1 != 'undefined' && (typeof arg1 == 'number' || arg1 == 'slow' || arg1 == 'fast')) {sR.thisCallArgs.slideSpeed = arg1;}else{sR.thisCallArgs.slideSpeed = sR.defaults.slideSpeed;}if(typeof arg2 == 'string'){sR.thisCallArgs.easing = arg2;}else if(typeof arg2 == 'function'){sR.thisCallArgs.callback = arg2;}else if(typeof arg2 == 'undefined') {sR.thisCallArgs.easing = sR.defaults.easing;}if(typeof arg3 == 'function') {sR.thisCallArgs.callback = arg3;}else if(typeof arg3 == 'undefined' && typeof arg2 != 'function'){sR.thisCallArgs.callback = sR.defaults.callback;}var $cells = $(this).find('td');$cells.wrapInner('<div class="slideRowDown" style="display:none;" />');$cellContentWrappers = $cells.find('.slideRowDown');$(this).show();$cellContentWrappers.slideDown(sR.thisCallArgs.slideSpeed, sR.thisCallArgs.easing, function() { $(this).replaceWith( $(this).contents()); });var wait = setInterval(function () {if($cellContentWrappers.is(':animated') === false) {clearInterval(wait);if(typeof sR.thisCallArgs.callback == 'function') { sR.thisCallArgs.callback.call(this);}}}, 100);return $(this);}}};
$.fn.slideRow = function(method,arg1,arg2,arg3) {if(typeof method != 'undefined') {if(sR.methods[method]) {return sR.methods[method].apply(this, Array.prototype.slice.call(arguments,1));}}};})(jQuery);
(function ($) {var internal = {clear : function () {if (window.getSelection) {var selection = window.getSelection();selection.removeAllRanges();} else if (document.selection.createRange) {document.selection.empty();}},getTextNode : function (element) {var domObj = $(element).first().get(0);if (window.getSelection && domObj.childNodes) {var nodes = domObj.childNodes;for ( var i = 0; i < nodes.length; i++) {var node = nodes[i];if (node.nodeType === 3) {return node;}}} else if (document.selection) {return domObj;}return false;},getElement: function (element){return $(element).first().get(0);}};var methods = {getRange : function () {var rangeRtn = {start : 0,startElement : null,end : 0,endElement : null};if (window.getSelection) {var selection = window.getSelection();var rangeObj = selection.getRangeAt(0);rangeRtn.start = rangeObj.startOffset;rangeRtn.startElement = rangeObj.startContainer;rangeRtn.end = rangeObj.endOffset;rangeRtn.endElement = rangeObj.endContainer;} else if (document.selection) {var rangeRtnObj = document.selection.createRange();var startRange = rangeRtnObj.duplicate();var endRange = rangeRtnObj.duplicate();startRange.collapse(true);endRange.collapse(false);rangeRtn.startElement = startRange.parentElement();rangeRtn.endElement = endRange.parentElement();var startElPos = rangeRtnObj.duplicate();startElPos.moveToElementText(startRange.parentElement());startElPos.setEndPoint('EndToStart', startRange);rangeRtn.start = startElPos.text.length;var endElPos = rangeRtnObj.duplicate();endElPos.moveToElementText(endRange.parentElement());endElPos.setEndPoint('EndToStart', endRange);rangeRtn.end = endElPos.text.length;}return rangeRtn;},setRange : function (range) {internal.clear();if (typeof range !== 'object') {return;}var startNode = false;var endNode = false;if (window.getSelection) {var selection = window.getSelection();var rangeObj = document.createRange();if (range.startElement !== undefined) {startNode = internal.getTextNode(range.startElement);}if (range.endElement !== undefined) {endNode = internal.getTextNode(range.endElement);}if (range.start !== undefined && startNode) {rangeObj.setStart(startNode, range.start);}if (range.end !== undefined && endNode) {rangeObj.setEnd(endNode, range.end);}selection.addRange(rangeObj);} else if (document.selection) {var rangeObj = document.body.createTextRange();if (range.startElement !== undefined) {startNode = internal.getElement(range.startElement);}if (range.endElement !== undefined) {endNode = internal.getElement(range.endElement);}if (range.start && startNode) {var startRange = rangeObj.duplicate();startRange.moveToElementText(startNode);startRange.move('character', range.start);rangeObj.setEndPoint('StartToStart', startRange);}if (range.end && endNode) {var endRange = rangeObj.duplicate();endRange.moveToElementText(endNode);endRange.move('character', range.end);rangeObj.setEndPoint('EndToEnd', endRange);}rangeObj.select();}return this;},select : function () {internal.clear();for ( var i = 0; i < this.length; i++) {var element = this[i];if (window.getSelection) {var selection = window.getSelection();var textNode = element.firstChild;if (textNode && textNode.data.length > 1) {var rangeObj = document.createRange();rangeObj.selectNode(element);selection.addRange(rangeObj);}} else {var rangeObj = document.body.createTextRange();rangeObj.moveToElementText(element);rangeObj.select();}}return this;},clear : function () {internal.clear();return this;},remove : function () {if (window.getSelection) { var selection = window.getSelection();try {selection.deleteFromDocument();} catch (e) {}if (!selection.isCollapsed) {var selRange = selection.getRangeAt(0);selRange.deleteContents();}if (selection.anchorNode) {selection.collapse(selection.anchorNode, selection.anchorOffset);}} else if (document.selection) {document.selection.clear();}return this;},toString : function () {if (window.getSelection) {var selRange = window.getSelection();return selRange.toString();} else if (document.selection) {var textRange = document.selection.createRange();return textRange.text;}}};$.textSelect = $.fn.textSelect = function (method) {if (methods[method]) {return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));} else {return methods['toString'].apply(this, Array.prototype.slice.call(arguments, 1));}};})(jQuery);

(function ($) {

	$.fn.exist = function() {
		return this.size() > 0 ? true : false;
	}

	$.fn.slideDownFadeIn = function() {
		return this.animate({ 'opacity' : 'show' , 'height' : 'show' });
	}

	$.fn.slideUpFadeOut = function() {
		return this.animate({ 'opacity' : 'hide' , 'height' : 'hide' });
	}

})(jQuery);

jQuery(document).ready( function($) {
	
	$('.av-option-panel').find('.av-tab-panel:first').fadeIn();
	
	
	$('.av-file-sc').hover( function(){
		
		$(this).textSelect('select')
	
	});
	
	$('.av-help-sec').find('.q').click( function(){
		var __s = $(this).siblings('.a');
		if( __s.is(':visible') )
			__s.slideUpFadeOut();
		else
			__s.slideDownFadeIn();
	});
	
	$('.av-option-panel').find('.nav-tab').click( function(){
		$('.nav-tab').removeClass('nav-tab-active');
		$(this).addClass('nav-tab-active');
		var _go = $(this).attr('go');
		
		$('.av-option-panel').find('.av-tab-panel:visible').animate({height:'hide',opacity:'hide'},'normal',function(){
			$('.av-option-panel').find('#'+_go).animate({height:'show',opacity:'show'},'normal',function(){
				refresh_av_editors();
			});
		});
	});
	
	$('#av-save-settings').click( function(){
		var _serialized = $(this).closest('form').serialize();
		var ajax_url = $(this).attr('ajax_url');
		var _this = $(this);
		$('#av_settings_page_preloader').fadeIn();
		_this.attr('disabled','disabled');
		$.ajax({
			type: 'POST',
			dataType: 'html',
			url: ajax_url,
			data:{
				action: 'av_ajax_settings_save',
				data: _serialized,
				vip_cats: $('#vip_categories').val(),
				vip_roles: $('#default_vip_roles').val()
			},
			success: function(data, textStatus, XMLHttpRequest){
				_this.removeAttr('disabled');
				$('#av_settings_page_preloader').fadeOut();
				if( data == 'ok' ){
					$('#ad_settings_saved').fadeIn();
					setTimeout( function(){
						$('#ad_settings_saved').fadeOut();
					}, 1500);
				} else {
					$('#ad_settings_save_error').fadeIn();
					setTimeout( function(){
						$('#ad_settings_save_error').fadeOut();
					}, 5500);
				}
				console.log(data);
			},
			error: function(MLHttpRequest, textStatus, errorThrown){
				alert('خطایی رخ داده است!.');
				$('#av_settings_page_preloader').fadeOut();
			}
		});
		return false;
	});
	
	$('#av-settings-form').submit( function(){
		return false;
	});
	
	
	$('body').on('keypress', '.av_draggable_item input:text', function(e){
		if(e.which == 13)
		return false;
	});
	

	$('#new-time-field').click( function(){
		var new_html = '<div class="av_draggable_item" style="display:none;">';
		new_html += '<div><label>آی دی بازه (هر بازه زمانی باید دارای یک آی دی منحصر به فرد باشد)</label></div>';
		new_html += '<div><input type="text" name="vip_time_id[]"/></div>';
		new_html += '<div><label>نام نمایشی بازه (نامی که به عنوان عنوان بازه زمانی در فرم خرید نمایش داده می شود)</label></div>';
		new_html += '<div><input type="text" name="vip_time_name[]"/></div>';
		new_html += '<div><label>هزینه این بازه زمانی (تمام هزینه ها به تومان هستند)</label></div>';
		new_html += '<div><input type="text" name="vip_time_price[]"/></div>';
		new_html += '<div><label>مدت زمان این آیتم (مقدار وارد شده به روز باید باشد مثلا برای یک ماه باید مقدار 30 وارد شود.</label></div>';
		new_html += '<div><input type="text" name="vip_time[]"/></div>';
		new_html += '<button class="av_remove-draggble_item">حذف این آیتم</button><div class="clr"></div>';
		new_html += '</div>';
		$('#vip_times').append(new_html);
		$('#vip_times').fadeIn();
		$('.av_draggable_item').fadeIn();
	});
	
	$('body').on( 'click', '.av_remove-draggble_item', function(){
		$(this).closest('.av_draggable_item').slideUp('fast',function(){
			$(this).remove();
		});
	});
	
	
	$("#vip_times").sortable({
		tolerance: "pointer",
		axis: "y",
		placeholder: "ui-state-highlight",
		opacity: 0.7,
		start: function(e, ui){
			$(ui.placeholder).hide(300);
		},
		change: function (e,ui){
			$(ui.placeholder).hide().show(300);
		}
	});
	
	
	$('#av-new-coupon').click( function(){
		var new_coupon_html = '<div class="av_draggable_item" style="display:none;">';
		new_coupon_html += '<div><label>نام کوپن (نامی که کاربر باید آن را وارد کند)</label></div>'
		new_coupon_html += '<div><input type="text" value="" name="coupon_name[]"></div>'
		new_coupon_html += '<div><label>تخفیف (مقداری هزینه ای که با وارد کردن کوپن از مبلغ کل کم می شود) به تومان</label></div>'
		new_coupon_html += '<div><input type="text" value="" name="coupon_discount[]"></div>'
		new_coupon_html += '<button class="av_remove-draggble_item">حذف این آیتم</button><div class="clr"></div>'
		new_coupon_html += '</div>';
		$('#vip_coupons').append(new_coupon_html);
		$('#vip_coupons').fadeIn();
		$('.av_draggable_item').fadeIn();
	
	});
	
	
	$("#vip_coupons").sortable({
		tolerance: "pointer",
		axis: "y",
		placeholder: "ui-state-highlight",
		opacity: 0.7,
		start: function(e, ui){
			$(ui.placeholder).hide(300);
		},
		change: function (e,ui){
			$(ui.placeholder).hide().show(300);
		}
	});
	
	$('.av-button-new-file').click( function(){
		$('.av-vip-files').hide("slide",{},'fast',function(){
			$('.av-upload-file').show("slide",{},'fast');
		});
		return false;
	});
	
	$('.av-button-files-list').click( function(){
		$('.av-upload-file').hide("slide",{},'fast',function(){
			$('.av-vip-files').show("slide",{},'fast');
		});
		return false;
	});
	
	$('#more-files').click( function(){
		$('.av-upload-files-appenable').append('<div class="av-vip-file-item" style="display:none;"><input type="file" name="vip_file[]" /><span class="delete_field">حذف فیلد</span></div>');		
		$('.av-vip-file-item').fadeIn();		
		return false;
	});
	
	$('.av-delete-file').click( function(){
		file_id = $(this).attr('file_id');
		ajax_url = $(this).attr('ajax_url');
		_tr = $(this).closest('tr');
		if( confirm('از انجام این عملیات مطمئن هستید؟') ){
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: ajax_url,
				data:{
					action: 'av_ajax_delete_file',
					file_id: file_id
				},
				success: function(data, textStatus, XMLHttpRequest){
					if( data.status == 'success' ){
						_tr.find('*').animate({color:'#FFFFFF'});
						_tr.delay(500).animate({ backgroundColor:'#FF4535' },'fast',function(){
							_tr.slideRow('up');
						});
					}else{
						alert('خطایی رخ داده است.');
					}
					console.log(data);
				},
				error: function(MLHttpRequest, textStatus, errorThrown){
					alert('خطایی رخ داده است.');
				}
			});
			
			
		}
		return false;
	});
	
	
	$('body').on( 'click', '.delete_field', function(){
		$(this).closest('div').fadeOut(function(){
			$(this).remove();
		});
	});
	
	$('#av-actions-list-btn').find('.av-button').toggle( 
	function(){
		$('#av-actions-list').show('slide',{'direction':'right'},'fast');
	},function(){
		$('#av-actions-list').hide('slide',{'direction':'right'},'fast');
	});
	
	$('#charge_date').datepicker();
	$("#users_list").select2();
	$("#vip_categories").select2();
	
	av_editors = Array;
	$('.av-code').each(function(index) {
		var __this = this;
		$(this).attr('id', 'av-code-' + index);
		var _code_type = typeof $(this).data('lang') == 'undefined' ? 'text/html' : $(this).data('lang');
		var tesdex = 'abcd' + index;
		var _line_numbers_ = true;
		var _auto_close_brackets = true;
		var _auto_close_tags = true;
		tesdex = CodeMirror.fromTextArea(
			document.getElementById('av-code-' + index), 
			{
				mode: _code_type,
				lineNumbers: _line_numbers_,
				autoCloseBrackets: _auto_close_brackets,
				autoCloseTags: _auto_close_tags,
			}
		);
		tesdex.on("change", function() {
			$(__this).val( tesdex.getValue() );
		});
		tesdex.refresh();
		tesdex.setSize(782, 400);
		av_editors[ index ]	= tesdex;
	});
	function refresh_av_editors(){
		$.each( av_editors, function(i,o){
			o.refresh();
		});
	}
	
	
	$('select#charge_type').change( function(){
		_val = $(this).val();
		if( 'day' == _val ){
			$('#charge-day').delay(400).fadeIn();
			$('#charge-date').fadeOut();
		}
		if( 'date' == _val ){
			$('#charge-date').delay(400).fadeIn();
			$('#charge-day').fadeOut();
		}
	});
	
	
	$('#send-add-user-data').click( function(){
		_ajax_url = $(this).attr('ajax');
		_cre = $('#charge_type').val() == 'day' ? $('#charge_days').val() : $('#charge_date').val();
		$('.av-preloader').fadeIn();
		$.ajax({
			dataType: 'json',
			url: _ajax_url,
			type: 'POST',
			data:{
				action: 'av_ajax_add_vip_user',
				userID: $('#users_list').val(),
				cre: _cre
			},
			success: function(data, textStatus, XMLHttpRequest){
				if( data.status == 'success' ){
					$('.save_ok').fadeIn();
				}
				if( data.status == 'error' ){
					$('.save_error').fadeIn();
				}
				$('.av-preloader').fadeOut();
				console.log(data);
				setTimeout( function(){
					$('.save_error').fadeOut();
					$('.save_ok').fadeOut();
				},2000);
			},
			error: function(MLHttpRequest, textStatus, errorThrown){
				alert('خطایی رخ داده است.');
				$('.av-preloader').fadeOut();
			}
		});
		return false;
	});

	
	if( $('#sms_agancy').val() == 'parandsms' ){
		$('#parandsms').show();
		$('#smsdehi').hide();
	}
	if( $('#sms_agancy').val() == 'smsdehi' ){
		$('#parandsms').hide();
		$('#smsdehi').show();
	}
	
	
	$('#sms_agancy').change( function(){
		
		if( $(this).val() == 'smsdehi' ){
			$('#parandsms').hide(500);
			$('#smsdehi').show(500);
		}
		
		if( $(this).val() == 'parandsms' ){
			$('#parandsms').show(500);
			$('#smsdehi').hide(500);
		}
	
	});
	
	
	$('#smsdehi_from').change( function(){
		if( $(this).val() == 'custom_number' )
			$('#smsdehi_custom_number').fadeIn();
		else	
			$('#smsdehi_custom_number').fadeOut();
	});
	
	if( $('#smsdehi_from').val() == 'custom_number' )
		$('#smsdehi_custom_number').show();
	else
		$('#smsdehi_custom_number').hide();
	
});


