$(document).ready(function() {
	
	var current_url = document.URL;
	if(current_url.match(/\/index$|\/index\/|index\.php/i)){
		$('.home').addClass('active');	
	}
	else if(current_url.match(/\/history$|\/history\/|history\.php/i)){
		$('.history').addClass('active');	
	}
	else if(current_url.match(/\/pay$|\/pay\/|pay\.php/i)){
		$('.send').addClass('active');	
	}
	else if(current_url.match(/\/receive$|\/receive\/|receive\.php/i)){
		$('.receive').addClass('active');	
	}
	else if(current_url.match(/\/settings$|\/settings\/|settings\.php/i)){
		$('.settings').addClass('active');	
	}
	
	$('.ui.dropdown').dropdown();
	$('.ui.sidebar').sidebar('attach events', '.toc.item');
	$('.rec_currency, .send_currency_edit').dropdown('set selected', 'btc');
	$('.lang_select').dropdown('set selected', lang);
	$('.cur_select').dropdown('set selected', currency);
	
	$(document).on('click', '.settings', function(){
		$('.settings_modal').modal('show');
	});
	
	$(document).on('click', '.show_tr_details', function(){
		var p = $(this).parents('table:first');
		
		$('.tr_details_div').slideUp();	
		$('.hide_tr_details').hide();
		$('.show_tr_details').show();
		
		$(this).hide();
		p.find('.hide_tr_details').show();
		p.find('.tr_details_div').slideDown();
		
		
	});
	
	$(document).on('click', '.hide_tr_details', function(){
		var p = $(this).parents('table:first');
		$(this).hide();
		p.find('.show_tr_details').show();
		p.find('.tr_details_div').slideUp();	
	});
	
	
	$(document).on('click', '.request_pay', function(e){
		e.preventDefault();
		$('.rec_notice').hide();
		var amount = $('input[name="amount"]').val();
		var currency = $('.rec_currency').dropdown('get value');
		var label = $('input[name="label"]').val();
		var desc = $('input[name="description"]').val();
		
		if(amount == '' || currency == '' || label == ''){
			return $('.rec_notice').html(fill_all_for_receive_lang).addClass('error').show(); 	
		}
		
		$('.receive_form').addClass('loading');
		
		$.post(ajax_url, {
			'request_pay': 1,
			'amount': amount,
			'currency': currency,
			'label': label,
			'description': desc,
			'nonce': ajax_nonce
		}, function(response){
			$('.receive_form').removeClass('loading');
			var data = $.parseJSON(response);
			if(data.error != ''){
				$('.rec_notice').html(data.error).addClass('error').show();
			}
			else {
				$('.rec_notice').html(data.html_code).addClass('success').show();
			}
		});	
	});
	
	$(document).on('click', '.decode_pay', function(e){
		e.preventDefault();
		$('.pay_notice').hide();
		var bolt11 = $('.pay_bolt11').val();
		
		if(bolt11 == ''){
			return $('.pay_notice').html(fill_all_for_receive).addClass('error').show(); 	
		}
		
		$('.pay_form').addClass('loading');
		
		$.post(ajax_url, {
			'decode_pay': 1,
			'bolt11': bolt11
		}, function(response){
			$('.pay_form').removeClass('loading');
			var data = $.parseJSON(response);
			if(data.error != ''){
				$('.pay_notice').html(data.error).addClass('error').show();
			}
			else {
				var date = new Date();
				var timestamp = Math.floor((new Date()).getTime() / 1000);
				var expires_at = timeConverter(data.expires_at);
				var expired = timestamp > data.expires_at;
				var html = '<form class="ui form pay_info_table_form"><div class="ui message pay_info_msg hidden"></div>'+
							'<div class="ui relaxed list pay_info_table">'+
							  '<div class="item">'+
								'<div class="content">'+
								  '<a class="header">'+payee_lang+'</a>'+
								  '<div class="description">'+
								  	data.raw_data.payee+
								  '</div>'+
								'</div>'+
							  '</div>'+
							  '<div class="item">'+
								'<div class="content">'+
								  '<a class="header">'+amount_lang+'</a>'+
								  '<div class="description send_amount_currency tr_table_amount" data-btc-amount="'+data.amount+'" data-btc-fixed="'+ (!data.amount ? 0 : 1) +'"><br/>'+
								  	'<span class="send_amount">' + data.amount+ '</span> <span class="send_currency">'  + data.currency+ '</span>' +
								  ( !data.amount ? '&nbsp;&nbsp;<i class="ui icon edit outline send_amount_edit" style="font-size:18px"></i>' : '' )+
								  '</div>'+
								'</div>'+
							  '</div>'+
							  '<div class="item">'+
								'<div class="content">'+
								  '<a class="header">'+expiry_lang+'</a>'+
								  '<div class="description expires_at" data="'+(expired ? '-1' : '1')+'" style="'+( timestamp > data.expires_at ? 'color:red' : '')+'">'+
								  	expires_at+ (expired ? ' ['+expired_lang+']' : '') + '<br/>' +
								  '</div>'+
								'</div>'+
							  '</div>'+
							  '<div class="item">'+
								'<div class="content">'+
								  '<a class="header">'+payment_hash_lang+'</a>'+
								  '<div class="description">'+
								  	data.raw_data.payment_hash+
								  '</div>'+
								'</div>'+
							  '</div>'+
							'</div>'+
							'<button class="ui fluid button red pay_now_btn">'+pay_now_lang+'</button>'+
							'<div class="ui pay_now_confirm_div" style="display:none">'+
								'<h2>'+are_you_sure_lang+'</h2>'+
								'<div class="ui stackable two column very relaxed grid">'+
									'<div class="ui column">'+
										'<button class="ui basic button huge green pay_now_sure_btn" data-rel="'+data.pay_ref_code+'">'+yes_lang+'</button>'+
									'</div>'+
									'<div class="ui column">'+
										'<button class="ui basic button huge violet pay_now_cancel_btn">'+no_lang+'</button>'+
									'</div>'+
								'</div>'+
							'</div>'+
						'</form>';
				$('.pay_notice').html(html).removeClass('error').show();
			}
		});	
	});
	
	$(document).on('click', '.pay_now_btn', function(e){
		e.preventDefault();
		$('.pay_info_msg').hide();
		var expired = $('.expires_at').attr('data');
		//if(expired == -1)return $('.pay_info_msg').html(payment_req_expired_lang).addClass('error').show();
		$(this).hide();
		$('.pay_now_confirm_div').show();
	});
	
	
	$(document).on('click', '.pay_now_cancel_btn', function(e){
		e.preventDefault();
		$('.pay_info_msg').hide();
		$('.pay_now_confirm_div').hide();
		$('.pay_now_btn').show();
	});
	
	$(document).on('click', '.send_amount_edit', function(e){
		e.preventDefault();
		$('.send_currency_modify_modal').modal('show');
	});
	
	$(document).on('click', '.edit_amount_send_btn', function(e){
		e.preventDefault();
		var amount = $('input[name="send_amount_edit"]').val();
		var currency = $('.send_currency_edit').dropdown('get value');
		
		if(currency == 'btc')btc = amount;
		else btc = amount; //currency conversion
		
		$('.send_amount').html(amount);
		$('.send_currency').html(currency.toUpperCase());
		
		$('.send_amount_currency').attr('data-btc-amount', btc);
		
		$('.send_currency_modify_modal').modal('hide');
	});	
	
	$(document).on('click', '.pay_now_sure_btn', function(e){
		e.preventDefault();
		$('.pay_info_msg').hide();
		$('.pay_info_table_form').addClass('loading');
		
		var pay_ref = $(this).attr('data-rel');
		var amount_btc = $('.send_amount_currency').attr('data-btc-amount');
		var fixed_amount = $('.send_amount_currency').attr('data-btc-fixed');
		
		$.post(ajax_url, {
			'pay_now': 1,
			'pay_ref': pay_ref,
			'amount_btc': amount_btc,
			'fixed_amount': fixed_amount
		}, function(response){
			$('.pay_info_table_form').removeClass('loading');
			var data = $.parseJSON(response);
			if(data.error != ''){
				$('.pay_now_confirm_div').hide();
				$('.pay_now_btn').show();
				$('.pay_info_msg').html(data.error).addClass('error').show();
			}
			else {
				$('.pay_info_table_form').slideUp();
				$('.pay_notice').html(pay_success_lang).removeClass('error').show();
			}
		});
	});
	
	
	$(document).on('click', '.open_fund_channel', function(e){
		e.preventDefault();
		$('.fundch_info_msg').hide();
		$('.fundch_form').addClass('loading');
		
		var node_addr = $('input[name="node_addr"]').val();
		var amount = $('input[name="amount"]').val();
		var currency = $('.fundch_currency').dropdown('get value');
		
		$.post(ajax_url, {
			'open_fund_channel': 1,
			'node_addr': node_addr,
			'amount': amount,
			'currency': currency
		}, function(response){
			$('.fundch_form').removeClass('loading');
			var data = $.parseJSON(response);
			if(data.error != ''){
				$('.fundch_info_msg').html(data.error).addClass('error').show();
			}
			else {
				$('.fundch_info_msg').html(fundch_success_lang).removeClass('error').show();
			}
		});
	});
	
	$(document).on('click', '.login_submit', function(e){
		e.preventDefault();
		$('.login_message').hide();
		$('.login_form').addClass('loading');
		
		var password = $('input[name="password"]').val();
		
		$.post(ajax_url, {
			'login': 1,
			'password': password
		}, function(response){
			$('.login_form').removeClass('loading');
			var data = $.parseJSON(response);
			if(data.error != ''){
				$('.login_message').html(data.error).addClass('error').show();
			}
			else {
				window.location.href = 'index.php';
			}
		});
	});
	
	$(document).on('submit', 'form', function(e){
		e.preventDefault();
	});
	
	
	
	
});

function timeConverter(UNIX_timestamp){
  var a = new Date(UNIX_timestamp * 1000);
  var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  var year = a.getFullYear();
  var month = months[a.getMonth()];
  var date = a.getDate();
  var hour = a.getHours().pad();
  var min = a.getMinutes().pad();
  var sec = a.getSeconds().pad();
  var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec ;
  return time;
}

Number.prototype.pad = function(size) {
    var s = String(this);
    while (s.length < (size || 2)) {s = "0" + s;}
    return s;
}