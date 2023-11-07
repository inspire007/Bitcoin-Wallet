<div class="ui middle aligned center aligned stackable grid">
  <div class="column payment_col">
    <div class="ui floating massive message">
        <?php echo _('Open Channel')?>
    </div>
    <div class="fundch_info_msg ui message hidden"></div>
    <form class="ui form fundch_form">
    <div class="ui huge form">
      <div class="field">
        <label><?php echo _('Node Address')?></label>
        <div class="ui input">
            <input type="text" name="node_addr" placeholder="<?php echo _('nodeid@ip:port')?>"/>
        </div>
      </div>
     
      <div class="ui middle aligned center aligned stackable grid statistics">
  		 <div class="statistic">
            <div class="value">
              <i class="bitcoin icon"></i> <?php echo satoshi_to_btc($cached_data['balance']['data'])?>
            </div>
            <div class="label">
              <?php echo _('Available Funds')?>
            </div>
         </div>
  	  </div>
     
     <div class="field">
        <label><?php echo _('Fund')?></label>
        <div class="ui right labeled input">
            <input type="text" name="amount" placeholder="<?php echo _('Enter amount in USD or BTC')?>"/>
            <div class="ui dropdown label fundch_currency">
                <div class="text">BTC</div>
                <i class="dropdown icon"></i>
                <div class="menu">
                	<div class="item rec_btc selected">BTC</div>
                  	<div class="item rec_usd disabled">USD</div>
                </div>
            </div>
        </div>
      </div>
   </div>
   <p></p>
   <div class="ui stackable two column very relaxed grid">
   		<div class="ui column">
        	<button class="ui basic button huge green open_fund_channel"><?php echo _('OPEN CHANNEL')?></button>
        </div>
        <div class="ui column">
        	<button class="ui basic button huge red"><?php echo _('CANCEL')?></button>
        </div>
   </div>
   </form>
  </div>
</div>