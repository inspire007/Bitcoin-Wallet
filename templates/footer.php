<?php if(!defined('__ROOT__'))exit();?>

<div class="ui inverted segment">
  <div class="ui container">
  		<div class="ui floating dropdown labeled search icon button lang_select">
          <i class="world icon"></i>
          <span class="text"><?php echo _('Select Language')?></span>
          <div class="menu">
            <div class="item">EN</div>
          </div>
        </div>

        <div class="ui floating dropdown labeled search icon button cur_select">
          <i class="dollar sign icon"></i>
          <span class="text"><?php echo _('Select Currency')?></span>
          <div class="menu">
            <div class="item">USD</div>
            <div class="item">EUR</div>
            <div class="item">GBP</div>
          </div>
        </div>
  </div>
</div>

</body>
</html>