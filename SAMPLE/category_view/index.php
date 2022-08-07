<table border="1" width="100%">
    <tr>
		%%thth%%
		<th>Actions</th>
    </tr>
	<?php foreach($%%tblname%% as $c){ ?>
    <tr>
		%%tdtd%%
		<td>
            <a href="<?php echo site_url('%%tblname%%/edit/'.$c['id']); ?>">Edit</a> | 
            <a href="<?php echo site_url('%%tblname%%/remove/'.$c['id']); ?>">Delete</a>
        </td>
    </tr>
	<?php } ?>
</table>
<div class="pull-right">
    <?php echo $this->pagination->create_links(); ?>    
</div>
