<?php use_helper('Object')?>
<?php echo form_tag('establecimiento/actual')?>

    <div align="center">Seleccione el establecimiento que desea administrar
    <br><br>
    <?php echo select_tag('establecimiento',objects_for_select($establecimientos,'getId','getNombre', $sf_user->getAttribute('fk_establecimiento_id')))?>
    <?php echo input_hidden_tag('referer', $referer) ?>
    <br><br>
    <?php echo submit_tag('Seleccionar')?>
    </div>
    
</form>