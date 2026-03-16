<?php
/**
 * This view is included into all desktop full views. It contains the footer of the application.
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link       https://github.com/bbalet/jorani
 * @since      0.1.0
 */
?>

            </div><!-- /container -->
        <div id="push"></div>
    </div><!-- /wrap -->
    <!-- FOOTER -->
    <div class="row" id="footer">
        <div class="span8 pull-left" id="rum_info">
            &nbsp;
        </div>
        <div class="span4 pull-right">
              <?php switch ($language_code){
                  case 'fr' : echo '<a class="anchor" href="https://fr.jorani.org/" target="_blank">Jorani</a>'; break;
                  default : echo '<a class="anchor" href="https://jorani.org/" target="_blank">Jorani</a>'; break;
              } ?>
          &nbsp;v<?php echo $GLOBALS['versionOfJorani'];?>&nbsp;&copy;2014-2023 Benjamin BALET
        </div>
    </div>
    </div>
    <!--Minimal profiling info //-->
<?php
if ($this->config->item("enable_apm_rum")) {
    //See. http://techblog.constantcontact.com/software-development/measure-page-load-times-using-the-user-timing-api/
    // Determine which databases are currently used
    foreach (get_object_vars($this) as $CI_object) {
        if (is_object($CI_object) && is_subclass_of(get_class($CI_object), 'CI_DB')) {
            $dbs[] = $CI_object;
        }
    }
    $query_time = 0;
    $query_count = 0;
    foreach ($dbs as $db) {
        foreach ($db->queries as $key => $val) {
            $query_time += $db->query_times[$key];
            $query_count++;
        }
    }
    $query_time = (int) round($query_time * 1000, 0);
    echo "\t<input id='ci_database_time' type='hidden' value='" . $query_time . "' />" . PHP_EOL;
    echo "\t<input id='ci_database_count' type='hidden' value='" . $query_count . "' />" . PHP_EOL;
    //Memory usage
    if (function_exists('memory_get_usage') && ($usage = memory_get_usage()) != '') {
        echo "\t<input id='ci_memory_usage' type='hidden' value='" . $usage . "' />" . PHP_EOL;
    } else {
        echo "\t<input id='ci_memory_usage' type='hidden' value='XXX' />" . PHP_EOL;
    }
    //Total time
    $total_time =  floatval($this->benchmark->elapsed_time('total_execution_time_start', 'total_execution_time_end'));
    $total_time = (int) round($total_time * 1000, 0);
    $total_time -= $query_time;
    echo "\t<input id='ci_elapsed_time' type='hidden' value='" . $total_time . "' />" . PHP_EOL;
}
?>
<?php if ($this->config->item("enable_apm_display")) { ?>
<script type="text/javascript">
// Add a load event listener that display web timing
window.addEventListener("load", displayRUMInfo, false);
function displayRUMInfo() {
  var perfData = window.performance.timing;
  var pageLoadTime = parseInt(perfData.domComplete - perfData.domLoading);
  var networkLatency = parseInt(perfData.responseEnd - perfData.requestStart);
  var ciElapsedTime = parseInt($("#ci_elapsed_time").val());
  var ciDatabaseTime = parseInt($("#ci_database_time").val());
  var total = ciDatabaseTime + ciElapsedTime + networkLatency + pageLoadTime;
  var content = '<i class="mdi mdi-memory" aria-hidden="true" title="Memory"></i>&nbsp;';
  content += $("#ci_memory_usage").val() + ' bytes ';
  content += '<i class="mdi mdi-clock" aria-hidden="true" title="Total time for user"></i>&nbsp;';
  content += total + ' ms ';
  content += '&nbsp;&nbsp;&mdash;&nbsp;&nbsp;';
  content += '<i class="mdi mdi-database" aria-hidden="true" title="SQL execution time (number of queries)"></i>&nbsp;';
  content += ciDatabaseTime + ' ms (' + $("#ci_database_count").val() + ') ';
  content += '&nbsp;';
  content += '<i class="mdi mdi-code-string" aria-hidden="true" title="PHP Execution time"></i>&nbsp;';
  content += ciElapsedTime + ' ms ';
  content += '&nbsp;';
  content += '<i class="mdi mdi-download" aria-hidden="true" title="Download time"></i>&nbsp;';
  content += networkLatency + ' ms ';
  content += '&nbsp;';
  content += '<i class="mdi mdi-internet-explorer" aria-hidden="true" title="Client processing time"></i>&nbsp;';
  content += pageLoadTime + ' ms ';
  $("#rum_info").html(content);
}
 </script>
<?php } ?>
</body>
</html>
