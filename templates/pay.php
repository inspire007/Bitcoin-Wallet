<div class="ui middle aligned center aligned stackable grid">
  <div class="column payment_col">
    <div class="ui floating massive message">
        <?php echo _('Payment Request')?>
    </div>
    <div class="ui pay_notice message hidden">
    </div>
    <form class="ui form pay_form">
    <div class="ui huge form">
      <div class="field">
        <label><?php echo _('Paste bolt11 code below')?></label>
        <textarea rows="6" class="pay_bolt11"></textarea>
      </div>
   </div>
   <p></p>
   <div class="ui stackable three column very relaxed grid">
   		<div class="ui column">
        	<button class="ui basic button huge green decode_pay"><?php echo _('DECODE')?></button>
        </div>
        <div class="ui column">
        	<button class="ui basic button huge violet"><?php echo _('SCAN')?></button>
        </div>
        <div class="ui column">
        	<button class="ui basic button huge red"><?php echo _('CANCEL')?></button>
        </div>
   </div>
   </form>
  </div>
</div>

<div class="ui modal mini send_currency_modify_modal">
  <div class="header"><?php echo _('Edit amount')?></div>
  <div class="content">
  	<form class="ui form">
    <div class="field">
        <label><?php echo _('Amount to send')?></label>
        <div class="ui right labeled input">
            <input type="text" name="send_amount_edit" placeholder="<?php echo _('Enter amount in USD or BTC')?>"/>
            <div class="ui dropdown label send_currency_edit">
                <div class="text">BTC</div>
                <i class="dropdown icon"></i>
                <div class="menu">
                	<div class="item rec_btc selected">BTC</div>
                  	<div class="item rec_usd disabled">USD</div>
                </div>
            </div>
        </div>
      </div>
      <button class="ui fluid black button edit_amount_send_btn"><?php echo _('Save')?></button>
      </form>
  </div>
</div>