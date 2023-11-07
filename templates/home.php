<div class="ui container">
  <h2 class="ui left floated header"><img class="ui avatar image btc_bal_image" src="images/lightning.png"/><span>Balance: <span class="home_big_balance"><?php echo satoshi_to_btc($cached_data['balance']['data'])?></span> BTC</span></h2>
  <h2 class="ui right floated header">
  	<a class="ui button" href="<?php echo makeuri("pay.php")?>">Pay</a>
    <a class="ui button" href="<?php echo makeuri("receive.php")?>">Receive</a>
    <a class="ui button" href="<?php echo makeuri("fundchannel.php")?>">Fund Channel</a>
  </h2>
  <div class="ui clearing divider"></div>
  <p></p>
  
  <div class="ui stackable two column very relaxed grid">
    <div class="ui eight wide column">
      <h2>Transaction History</h2>
        <div class="ui vertical segment">
        
        	<?php if(empty($cached_data['history']['data'])):?>
        	<!-- empty transaction list-->
        	<div class="ui success message">
              <div class="header">
                <?php echo _('No transaction history!');?>
              </div>
              <p><?php echo _('Nothing found. Send or receive money to have something here!');?></p>
            </div>
            <!--/ empty transaction list-->
        	<?php else:?>
            
        	
        	<!-- php code loop  -->
     		<?php echo get_transaction_history_table_html(3, 1)?>
            <!--/ php code loop  -->
            
            <a class="fluid ui blue button" href="<?php echo makeuri("history.php")?>"><?php echo _('View all transactions')?></a>
            
            <?php endif;?>
            
        </div>
    </div>
    
    <div class="ui eight wide column ltd-status">
      <h2><?php echo _('Lighting Status')?></h2>
      <div class="ui list">
      	  <!--
          <div class="item">
            <i class="address card icon"></i>
            <div class="content">
              <a class="header"><?php echo _('Node Id')?></a>
              <p></p>
              <div class="description bwrap"><?php echo $cached_data['info']['data']['id']?></div>
            </div>
          </div>
          <p></p>
          -->
          <div class="item">
            <i class="address card icon"></i>
            <div class="content">
              <a class="header"><?php echo _('Node Address')?></a>
              <p></p>
              <div class="description bwrap"><?php echo $cached_data['info']['data']['node_addr']?></div>
            </div>
          </div>
          <p></p>
          <div class="item">
          	<div class="ui stackable two column very relaxed grid">
                <div class="ui six wide column">
                <div class="ui list">
                  <div class="item">
                    <i class="linkify icon"></i>
                    <div class="content">
                      <a class="header"><?php echo _('Channeled')?></a>
                      <p></p>
                      <div class="description number"><?php echo $cached_data['info']['data']['num_active_channels']?></div>
                    </div>
                  </div>
                  <p></p>
                  <div class="item">
                    <i class="sign-out alternate icon"></i>
                    <div class="content">
                      <a class="header"><?php echo _('Sendable')?></a>
                      <p></p>
                      <div class="description number"><?php echo msatoshi_to_btc($cached_data['limits']['data']['sendable'])?></div>
                    </div>
                  </div>
                  <p></p>
                  <div class="item">
                    <i class="sign-in alternate icon"></i>
                    <div class="content">
                      <a class="header"><?php echo _('Receivable')?></a>
                      <p></p>
                      <div class="description number"><?php echo msatoshi_to_btc($cached_data['limits']['data']['receivable'])?></div>
                    </div>
                  </div>
                  <p></p>
                  <div class="item">
                    <i class="exchange icon"></i>
                    <div class="content">
                      <a class="header"><?php echo _('BTC/'.$settings['currency'].' Price')?></a>
                      <p></p>
                      <div class="description number"><?php echo $settings['exchange_rates']['ask']?>&nbsp;<?php echo $settings['currency']?></div>
                    </div>
                  </div>
                  <p></p>
                  </div>
                  </div>
                  <div class="ui ten wide column">
                    <div class="ui list">
                    <div class="item">
                    <i class="qrcode icon"></i>
                    <div class="content">
                      <a class="header"><?php echo _('Node QR')?></a>
                      <p></p>
                      <div class="description">
                        <img src="<?php echo generate_qr_code($cached_data['info']['data']['node_addr'])?>" />
                      </div>
                    </div>
                  </div>
                  </div>
                 </div>
              </div>
  		   </div>
        </div>
     </div>
  </div>
</div>