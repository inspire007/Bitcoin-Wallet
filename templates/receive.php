<div class="ui middle aligned center aligned stackable grid">
  <div class="column payment_col">
    <div class="ui floating massive message">
        <?php echo _('Request Payment')?>
    </div>
    <div class="ui rec_notice message hidden">
    </div>
    <form class="ui form receive_form">
    <div class="ui huge form">
      <div class="field">
        <label><?php echo _('Amount to receive')?></label>
        <div class="ui right labeled input">
            <input type="text" name="amount" placeholder="<?php echo _('Enter amount in USD or BTC')?>"/>
            <div class="ui dropdown label rec_currency">
                <div class="text">BTC</div>
                <i class="dropdown icon"></i>
                <div class="menu">
                	<div class="item rec_btc selected">BTC</div>
                  	<div class="item rec_usd disabled">USD</div>
                </div>
            </div>
        </div>
      </div>
      <div class="field">
        	<label><?php echo _('Label')?></label>
            <div class="ui input">
                <input type="text" name="label" placeholder="<?php echo _('Type invoice label')?>"/>
            </div>
      </div>
      <div class="field">
        	<label><?php echo _('Description')?></label>
            <div class="ui input">
                <input type="text" name="description" placeholder="<?php echo _('(Optional)')?>"/>
            </div>
      </div>
   </div>
   <p></p>
   <div class="ui stackable two column very relaxed grid">
   		<div class="ui column">
        	<button class="ui basic button huge green request_pay"><?php echo _('REQUEST')?></button>
        </div>
        <div class="ui column">
        	<button class="ui basic button huge red cancel_pay"><?php echo _('CANCEL')?></button>
        </div>
   </div>
  </div>
  </form>
</div>