<div class="ui middle aligned center aligned grid login_grid">
  <div class="column login_column">
    <h2 class="ui teal image header">
      <div class="content">
        <?php echo _('Log-in to your account')?>
      </div>
    </h2>
    <div class="ui message login_message hidden"></div>
    <form class="ui form login_form" method="post">
      <div class="ui stacked segment">
        <div class="field">
          <div class="ui massive left icon input">
            <i class="lock icon"></i>
            <input type="password" name="password" placeholder="<?php echo _('Password')?>">
          </div>
        </div>
        <div class="ui fluid large submit button login_submit"><?php echo _('Login')?></div>
      </div>
    </form>
  </div>
</div>