<div class="ui stackable container">
   <h2><?php echo _('Transaction History')?></h2>
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
    <?php echo get_transaction_history_table_html()?>
    <!--/ php code loop  -->
    <?php endif;?>              
</div>