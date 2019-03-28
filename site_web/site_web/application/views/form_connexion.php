<?php
?>
<?php echo validation_errors(); ?>
<?php echo form_open('formConnexion'); ?>
    <div class="form-group">
        <?php echo form_error('email'); ?>
        <input required name="email" value="<?php echo set_value('email'); ?>" placeholder="Email*" class="form-control form-control-sm" type="text" size="30">
    </div>
    <div class="form-group">
        <?php echo form_error('mdp'); ?>
        <input required name="mpd" type="password" value="<?php echo set_value('password'); ?>" placeholder="Mot de passe*" class="form-control form-control-sm">
    </div>
    <div class="form-group">
        <button required type="submit" class="btn btn-info btn-block">Login</button>
    </div>
    <div class="form-group text-info text-center ">
        <small><a href="#" data-toggle="modal" data-target="#modalInscription">Nouvel utilisateur ?</a></small>
    </div>
</form>

