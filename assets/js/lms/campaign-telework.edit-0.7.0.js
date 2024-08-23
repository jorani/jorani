/**
 * This Javascript code is used on the create/edit campaign
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.3.0
 */

//Detect overlapping cases (Ajax request)
function getCampaignInfos() {   
        $.ajax({
        type: "POST",
        url: baseURL + "teleworkcampaigns/validate",
        data: {   
                    startdate: $('#startdate').val(),
                    enddate: $('#enddate').val(),
                    campaign_id: campaignId
                }
        })
        .done(function(campaignInfo) {
            //Check if the current campaign overlaps with another one
            showOverlappingMessage(campaignInfo);
        });
}

//Display the list of non-working days occuring between the telework request start and end dates
function showOverlappingMessage(campaignInfo) {
    if (typeof campaignInfo.overlap !== 'undefined') {
        if (Boolean(campaignInfo.overlap)) {
        	$(".btn-primary").prop("disabled", true);
            $("#lblOverlappingAlert").show();
        } else {
        	$(".btn-primary").prop("disabled", false);
            $("#lblOverlappingAlert").hide();
        }
    }
}

$(function () {
    //Init the start and end date picker and link them (end>=date)
    $("#startdate").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: dateJsFormat,
        numberOfMonths: 1,
              onClose: function( selectedDate ) {
                $( "#enddate" ).datepicker( "option", "minDate", selectedDate );
              }
    }, $.datepicker.regional[languageCode]);
    $("#enddate").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: dateJsFormat,
        numberOfMonths: 1,
              onClose: function( selectedDate ) {
                $( "#startdate" ).datepicker( "option", "maxDate", selectedDate );
              }
    }, $.datepicker.regional[languageCode]);

	$('#startdate').change(function() {getCampaignInfos();});
    $('#enddate').change(function() {getCampaignInfos();});
    
    $("#frmTeleworkCampaignForm").submit(function(e) {
        if (validate_form()) {
            return true;
        } else {
            e.preventDefault();
            return false;
        }
    });
});