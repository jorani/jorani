<?php
//This is a sample page showing how to create a custom report
//We can get access to all the framework, so you can do anything with the instance of the current controller ($this)
?>
<h2>Ventilation par type de congé</h2>

<p>Exemple de rapport montrant la répartition des congés pris (regroupés par type).</p>

<div class="row-fluid">
    <div class="span4">
        <form action="<?php echo base_url();?>custom-report" method="GET">
                <label for="cboYear">Année
                <select name="cboYear">
                    <?php
                    $entity_name = $this->input->get('txtEntity', TRUE);
                    if (is_null($this->input->get('cboYear', TRUE))) {
                        $year =  date('Y');
                    } else {
                        $year =  $this->input->get('cboYear');
                    }
                    $len =  date('Y');
                    for ($ii=date('Y', strtotime('-6 year')); $ii<= $len; $ii++) {
                        if ($ii == $year) {
                            echo "<option val='" . $ii ."' selected>" . $ii ."</option>";
                        } else {
                            echo "<option val='" . $ii ."'>" . $ii ."</option>";
                        }
                    }?>
                </select></label><br />
                <label for="txtEntity">Entité
                <div class="input-append">
                <input type="text" id="txtEntity" name="txtEntity" value="<?php echo $entity_name; ?>" readonly />
                <button type="button" id="cmdSelectEntity" class="btn btn-primary">Choisir</button>
                </div></label>
                <input type="hidden" id="txtEntityID" name="txtEntityID" />
                <label for="chkIncludeChildren">
                <input type="checkbox" id="chkIncludeChildren" name="chkIncludeChildren" checked />&nbsp;Inclure les sous entitées</label><br />
                <button type="submit" id="cmdSubmit" class="btn btn-primary">Exécuter</button>
                <a href="#" id="tipReload" data-toggle="tooltip" title="Don't forget to reload the report" data-placement="bottom" data-container="#cmdSubmit"></a>
        </form>
    </div>
    <div class="span8">	
        <div id="chart"></div>
    </div>
</div>
<br />

<div id="frmSelectEntity" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmSelectEntity').modal('hide');" class="close">&times;</a>
         <h3>Choisir l'entité</h3>
    </div>
    <div class="modal-body" id="frmSelectEntityBody">
        <img src="<?php echo base_url();?>assets/images/loading.gif">
    </div>
    <div class="modal-footer">
        <a href="#" onclick="select_entity();" class="btn secondary">OK</a>
        <a href="#" onclick="$('#frmSelectEntity').modal('hide');" class="btn secondary">Annuler</a>
    </div>
</div>

<!--We can load any asset, just use the base_url methos for safer URLs//-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.6/d3.min.js"></script>
<script type="text/javascript">
function select_entity() {
    entity = $('#organization').jstree('get_selected')[0];
    entityName = $('#organization').jstree().get_text(entity);
    $('#txtEntity').val(entityName);
    $('#txtEntityID').val(entity);
    $("#frmSelectEntity").modal('hide');
    suggest_reload();
}

function suggest_reload() {
    $('#tipReload').tooltip('show');
    setTimeout(function() {$('#tipReload').tooltip('hide')}, 1000);
}
    
$(document).ready(function() {
    
    $('#chkIncludeChildren').change(function() {
        suggest_reload();
    });
    
    $("#frmSelectEntity").alert();
    
    $("#cmdSelectEntity").click(function() {
        $("#frmSelectEntity").modal('show');
        $("#frmSelectEntityBody").load('<?php echo base_url(); ?>organization/select');
    });
});

<?php
//If you want to load another model, you need to get the instance of the current controller
$ci = get_instance();
$ci->load->model('organization_model');
$entity = 0;
$include_children = TRUE;
if ($this->input->get('txtEntityID', TRUE) !== FALSE) {
    $entity = (int) $this->input->get('txtEntityID', TRUE);
    echo '$(\'#txtEntityID\').val(\'' . $this->input->get('txtEntityID', TRUE) .'\');';
}
$include_children = filter_var($this->input->get('chkIncludeChildren'), FILTER_VALIDATE_BOOLEAN);
if ($include_children) {
    echo '$(\'#chkIncludeChildren\').prop(\'checked\', true);';
} else {
    echo '$(\'#chkIncludeChildren\').prop(\'checked\', false);';
}
$entity = ($this->input->get('txtEntityID', TRUE) != FALSE)? $this->input->get('txtEntityID', TRUE) : 0;
if ($this->input->get('txtEntity', TRUE) != FALSE) {
    echo '$(\'#txtEntity\').val(\'' . $this->input->get('txtEntity', TRUE) .'\');';
} else {
    $entityName = $ci->organization_model->getName(0);
    echo '$(\'#txtEntity\').val(\'' . $entityName .'\');';
}

$users = $ci->organization_model->allEmployees($entity, $include_children);
$ids = array(0);
foreach ($users as $user) {
    array_push($ids, (int) $user->id);
}
?>
</script>
    
<script type="text/javascript">
var w = 400;
var h = 400;
var r = h/2;
var color = d3.scale.category20c();

var data = [
<?php
//$this is the instance of the current controller, so you can use it for direct access to the database?
$this->db->select('count(*) as number, sum(duration) as duration', FALSE);
$this->db->select('types.name as type_name');
$this->db->from('leaves');
$this->db->join('types', 'leaves.type = types.id');
$this->db->where('leaves.status', 3);
if (is_null($this->input->get('cboYear', TRUE))) {
    $this->db->where('YEAR(startdate) = YEAR(CURDATE())');
} else {
    $this->db->where('YEAR(startdate) = ' . $this->db->escape($this->input->get('cboYear', TRUE)));
}
$this->db->where_in('leaves.employee', $ids);
$this->db->group_by('type'); 
$this->db->order_by('number', 'desc');
$rows = $this->db->get()->result_array();

$total = 0;
foreach ($rows as $row) {
    $total += (float) $row['duration'];
    echo '{"label":"' . $row['type_name'] . '", "value":' . $row['duration'] . '},';
}
?>
];

var vis = d3.select('#chart').append("svg:svg").data([data]).attr("width", w).attr("height", h).append("svg:g").attr("transform", "translate(" + r + "," + r + ")");
var pie = d3.layout.pie().value(function(d){return d.value;});

// declare an arc generator function
var arc = d3.svg.arc().outerRadius(r);

// select paths, use arc generator to draw
var arcs = vis.selectAll("g.slice").data(pie).enter().append("svg:g").attr("class", "slice");
arcs.append("svg:path")
    .attr("fill", function(d, i){
        return color(i);
    })
    .attr("d", function (d) {
        return arc(d);
    });

// add the text
arcs.append("svg:text").attr("transform", function(d){
        d.innerRadius = 0;
        d.outerRadius = r;
    return "translate(" + arc.centroid(d) + ")";}).attr("text-anchor", "middle").text( function(d, i) {
    return data[i].label;}
);
</script>

<div class="row-fluid">
    <div class="span4">&nbsp;</div>
    <div class="span8">
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                      <th>Type de congé</th>
                      <th>Nombre de jours</th>
                      <th>Pourcentage</th>
                      <th>Demandes</th>
                    </tr>
                  </thead>
                  <tbody>
<?php foreach ($rows as $row) {
    echo '<tr><td>' . $row['type_name'] . '</td><td>' . $row['duration'] . '</td><td>' . sprintf("%.2f%%", (((float) $row['duration'])  / $total) * 100) . '</td><td>' . $row['number'] . '</td></tr>';
}
   echo '<tr><td><b>TOTAL</b></td><td colspan="3"><b>' . $total . '&nbsp;jours</b></td></tr>';
?>
                  </tbody>
            </table>
    </div>
</div>

<?php 
$url_export = base_url() . 'excel-export' .
        '?cboYear=' . $year . 
        '&txtEntity=' . urlencode(is_null($entity_name)?"":$entity_name) .  
        '&txtEntityID=' . $entity . 
        '&chkIncludeChildren=' . $include_children;
 ?>

<a href="<?php echo base_url();?>sample-page" class="btn btn-primary"><i class="mdi mdi-arrow-left-bold"></i>&nbsp;Retour au formulaire</a>
<a href="<?php echo $url_export; ?>" class="btn btn-primary"><i class="mdi mdi-download"></i>&nbsp; Exporter vers Excel</a>

<div class="row-fluid"><div class="span12">&nbsp;</div></div>
