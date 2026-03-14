<?php
//This is a sample page showing how to create a custom report
//We can get access to all the framework, so you can do anything with the instance of the current controller ($this)

//You can load a language file so as to translate the report if the strings are available
//It can be useful for date formating
$this->lang->load('requests', $this->language);
$this->lang->load('global', $this->language);
?>

<h2>Toutes les demandes de congé</h2>

<?php
//$this is the instance of the current controller, so you can use it for direct access to the database
$this->db->select('users.firstname, users.lastname, leaves.*');
$this->db->select('status.name as status_name, types.name as type_name');
$this->db->from('leaves');
$this->db->join('status', 'leaves.status = status.id');
$this->db->join('types', 'leaves.type = types.id');
$this->db->join('users', 'leaves.employee = users.id');
$this->db->order_by('users.lastname, users.firstname, leaves.startdate', 'desc');
$rows = $this->db->get()->result_array();
?>

<div class="row-fluid">
    <div class="span12">
        <table class="table table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th><?php echo lang('requests_index_thead_id');?></th>
                    <th><?php echo lang('requests_index_thead_fullname');?></th>
                    <th><?php echo lang('requests_index_thead_startdate');?></th>
                    <th><?php echo lang('requests_index_thead_enddate');?></th>            
                    <th><?php echo lang('requests_index_thead_duration');?></th>
                    <th><?php echo lang('requests_index_thead_type');?></th>
                    <th><?php echo lang('requests_index_thead_status');?></th>
                </tr>
            </thead>
                  <tbody>
<?php foreach ($rows as $row) {
    $date = new DateTime($row['startdate']);
    $startdate = $date->format(lang('global_date_format'));
    $date = new DateTime($row['enddate']);
    $enddate = $date->format(lang('global_date_format'));?>
<tr>
    <td><a href="leaves/edit/<?php echo $row['id'];?>?source=hr%2Fleaves%2F1" target="_blank"><?php echo $row['id'];?></a></td>
    <td><a href="hr/counters/employees/<?php echo $row['employee'];?>" target="_blank"><?php echo $row['firstname'] . ' ' . $row['lastname']; ?></a></td>
    <td><?php echo $startdate . ' (' . lang($row['startdatetype']). ')'; ?></td>
    <td><?php echo $enddate . ' (' . lang($row['enddatetype']) . ')'; ?></td>
    <td><?php echo $row['duration']; ?></td>
    <td><?php echo $row['type_name']; ?></td>
    <td><?php echo lang($row['status_name']); ?></td>
</tr>
<?php } ?>
                  </tbody>
            </table>
    </div>
</div>

<a href="<?php echo base_url() . 'excel-export-all-leave-requests'; ?>" class="btn btn-primary"><i class="mdi mdi-download"></i>&nbsp; <?php echo lang('requests_index_button_export');?></a>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>
