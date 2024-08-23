/**
 * This Javascript code is used on the create/edit telework request
 * @copyright  Copyright (c) 2021 Maithyly SIVAPALAN
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.3.0
 */

//Try to calculate the length of the telework
function getTeleworkLength(refreshInfos) {
    refreshInfos = typeof refreshInfos !== 'undefined' ? refreshInfos : true;
    var start = moment($('#startdate').val());
    var end = moment($('#enddate').val());
    var startType = $('#startdatetype option:selected').val();
    var endType = $('#enddatetype option:selected').val();

    if (start.isValid() && end.isValid()) {
        if (start.isSame(end)) {
            if (startType == "Morning" && endType == "Morning") {
                $("#spnDayType").html("<img src='" + baseURL + "assets/images/telework_1d_MM.png' />");
            }
            if (startType == "Afternoon" && endType == "Afternoon") {
                $("#spnDayType").html("<img src='" + baseURL + "assets/images/telework_1d_AA.png' />");
            }
            if (startType == "Morning" && endType == "Afternoon") {
                $("#spnDayType").html("<img src='" + baseURL + "assets/images/telework_1d_MA.png' />");
            }
            if (startType == "Afternoon" && endType == "Morning") {
                $("#spnDayType").html("<img src='" + baseURL + "assets/images/date_error.png' />");
            }
        } else {
             if (start.isBefore(end)) {
                if (startType == "Morning" && endType == "Morning") {
                    $("#spnDayType").html("<img src='" + baseURL + "assets/images/telework_2d_MM.png' />");
                }
                if (startType == "Afternoon" && endType == "Afternoon") {
                    $("#spnDayType").html("<img src='" + baseURL + "assets/images/telework_2d_AA.png' />");
                }
                if (startType == "Morning" && endType == "Afternoon") {
                    $("#spnDayType").html("<img src='" + baseURL + "assets/images/telework_2d_MA.png' />");
                }
                if (startType == "Afternoon" && endType == "Morning") {
                    $("#spnDayType").html("<img src='" + baseURL + "assets/images/telework_2d_AM.png' />");
                }
             }
        }
        if (refreshInfos) getTeleworkInfos(false);
    }
}

//Get the telework credit, duration and detect overlapping cases (Ajax request)
//Default behavour is to set the duration field. pass false if you want to disable this behaviour
function getTeleworkInfos(preventDefault) {
        $('#frmModalAjaxWait').modal('show');
        var start = moment($('#startdate').val());
        var end = moment($('#enddate').val());
        
//Prevent the creation of telework at earlier dates by employees.       
// if ((moment().diff($('#startdate').val(), 'days') > 0 ||
// moment().diff($('#enddate').val(), 'days') > 0) && !isAdmin && !isHr &&
// !isManager) {
// $(".btn-primary").prop("disabled", true);
// $("#lblPasseAlert").show();
// //alert(window.location.href);
// } else {
// $(".btn-primary").prop("disabled", false);
// $("#lblPasseAlert").hide();
// }
        
        $.ajax({
        type: "POST",
        url: baseURL + "teleworks/validate",
        data: {   id: userId, 
                    startdate: $('#startdate').val(),
                    enddate: $('#enddate').val(),
                    startdatetype: $('#startdatetype').val(),
                    enddatetype: $('#enddatetype').val(),
                    telework_id: teleworkId
                }
        })
        .done(function(teleworkInfo) {
            if (typeof teleworkInfo.length !== 'undefined') {
                var duration = parseFloat(teleworkInfo.length);
                duration = Math.round(duration * 1000) / 1000;  //Round to 3 decimals only if necessary
                if (!preventDefault) {
                    if (start.isValid() && end.isValid()) {
                        $('#duration').val(duration);
                    }
                }
            }
            if (typeof teleworkInfo.credit !== 'undefined') {
                var credit = parseFloat(teleworkInfo.credit);
                var duration = parseFloat($("#duration").val());
                if (duration > credit) {
                    $("#lblCreditAlert").show();
                } else {
                    $("#lblCreditAlert").hide();
                }
                if (teleworkInfo.credit != null) {
                    $("#lblCredit").text('(' + teleworkInfo.credit + ')');
                }
            }
            //Check if the current request overlaps with another one
            showOverlappingMessage(teleworkInfo);
            showOverlappingLeavesMessage(teleworkInfo);
			//Or overlaps with a time organisation
			showOverlappingTimeOrganisationsMessage(teleworkInfo);
            //Or overlaps with a non-working day
            showOverlappingDayOffMessage(teleworkInfo);
            //Check if the current request exceeds the number of days allowed
            showLimitExceedingMessage(teleworkInfo);
			//Check the dates of telework requests that correspond to the valid campaigns
			showForCampaignDatesMessage(teleworkInfo);
			//Check the deadline for a telework request
			showDelayRespectedMessage(teleworkInfo);
			//Check the rights for half-day telework request
			showHalfdayTeleworkMessage(teleworkInfo);
			//Enable or disable the primary buttons
			primarybuttons(teleworkInfo);
            //Check if the employee has a contract
            if (teleworkInfo.hasContract == false) {
                bootbox.alert(noContractMsg);
            } else {
                //If the employee has a contract, check if the current telework request is not on two yearly telework periods
                var periodStartDate = moment(teleworkInfo.PeriodStartDate);
                var periodEndDate = moment(teleworkInfo.PeriodEndDate);
                if (start.isValid() && end.isValid() && periodEndDate.isValid()) {
                    if (start.isBefore(periodEndDate) && periodEndDate.isBefore(end)) {
                        bootbox.alert(noTwoPeriodsMsg);
                    }
                    if (start.isBefore(periodStartDate)) {
                        bootbox.alert(noTwoPeriodsMsg);
                    }
                }
            }
            showListDayOff(teleworkInfo);
            $('#frmModalAjaxWait').modal('hide');
        });
}

//When editing/viewing a telework request, refresh the information about overlapping and days off in the period
function refreshTeleworkInfo() {
        $('#frmModalAjaxWait').modal('show');
        var start = moment($('#startdate').val());
        var end = moment($('#enddate').val());
        $.ajax({
        type: "POST",
        url: baseURL + "teleworks/validate",
        data: {   id: userId,
                    startdate: $('#startdate').val(),
                    enddate: $('#enddate').val(),
                    startdatetype: $('#startdatetype').val(),
                    enddatetype: $('#enddatetype').val(),
                    telework_id: teleworkId
                }
        })
        .done(function(teleworkInfo) {
            showOverlappingMessage(teleworkInfo);
            showOverlappingLeavesMessage(teleworkInfo);
			showOverlappingTimeOrganisationsMessage(teleworkInfo);
            showOverlappingDayOffMessage(teleworkInfo);            
            showListDayOff(teleworkInfo);
            showLimitExceedingMessage(teleworkInfo);
			showForCampaignDatesMessage(teleworkInfo);
			showDelayRespectedMessage(teleworkInfo);
			showHalfdayTeleworkMessage(teleworkInfo);
			primarybuttons(teleworkInfo);
            $('#frmModalAjaxWait').modal('hide');
        });
}

//Display the list of non-working days occuring between the telework request start and end dates
function showListDayOff(teleworkInfo) {
    if (typeof teleworkInfo.listDaysOff !== 'undefined') {
        var arrayLength = teleworkInfo.listDaysOff.length;
        if (arrayLength>0) {
            var htmlTable = "<a href='#divDaysOff' data-toggle='collapse'  class='btn btn-primary input-block-level'>";
            htmlTable += listOfDaysOffTitle.replace("%s", teleworkInfo.lengthDaysOff);
            htmlTable += "&nbsp;<i class='icon-chevron-down icon-white'></i></a>\n";
            htmlTable += "<div id='divDaysOff' class='collapse'>";
            htmlTable += "<table class='table table-bordered table-hover table-condensed'>\n";
            htmlTable += "<tbody>";
            for (var i = 0; i < arrayLength; i++) {
                htmlTable += "<tr><td>";
                htmlTable += moment(teleworkInfo.listDaysOff[i].date, 'YYYY-MM-DD').format(dateMomentJsFormat);
                htmlTable += " / <b>" + teleworkInfo.listDaysOff[i].title + "</b></td>";
                htmlTable += "<td>" + teleworkInfo.listDaysOff[i].length + "</td>";
                htmlTable += "</tr>\n";
            }
            htmlTable += "</tbody></table></div>";
            $("#spnDaysOffList").html(htmlTable);
            var htmlTooltip = "<a href='#' id='showNoneWorkedDay' data-toggle='tooltip' data-toggle='tooltip' data-placement='right' title='";
            htmlTooltip += listOfDaysOffTitle.replace("%s", teleworkInfo.lengthDaysOff);
            htmlTooltip += "'><i class='icon-info-sign'></i></a>";
            $("#tooltipDayOff").html(htmlTooltip);
            $(function () {
              $('[data-toggle="tooltip"]').tooltip();
            });

        } else {
            //NOP
        }
    }
}

function showListDayOffHTML(){
  $('#frmModalAjaxWait').modal('show');
  var start = moment($('#startdate').val());
  var end = moment($('#enddate').val());
  $.ajax({
  type: "POST",
  url: baseURL + "teleworks/validate",
  data: {   id: userId,
              startdate: $('#startdate').val(),
              enddate: $('#enddate').val(),
              startdatetype: $('#startdatetype').val(),
              enddatetype: $('#enddatetype').val(),
              telework_id: teleworkId
          }
  })
  .done(function(teleworkInfo) {
      $('#frmModalAjaxWait').modal('hide');
      if (typeof teleworkInfo.listDaysOff !== 'undefined') {
          var arrayLength = teleworkInfo.listDaysOff.length;
          if (arrayLength>0) {
              var htmlTable = "<div id='divDaysOff2'>";
              htmlTable += "<table class='table table-bordered table-hover table-condensed'>\n";
              htmlTable += "<thead class='thead-inverse'>";
              htmlTable += "<tr><th>";
              htmlTable += listOfDaysOffTitle.replace("%s", teleworkInfo.lengthDaysOff);
              htmlTable += "</th></tr></thead>";
              htmlTable += "<tbody>";
              for (var i = 0; i < arrayLength; i++) {
                  htmlTable += "<tr><td>";
                  htmlTable += moment(teleworkInfo.listDaysOff[i].date, 'YYYY-MM-DD').format(dateMomentJsFormat);
                  htmlTable += " / <b>" + teleworkInfo.listDaysOff[i].title + "</b></td>";
                  htmlTable += "<td>" + teleworkInfo.listDaysOff[i].length + "</td>";
                  htmlTable += "</tr>\n";
              }
              htmlTable += "</tbody></table></div>";
              bootbox.alert(htmlTable, function() {
                console.log("Alert Callback");
              });
          } else {
              //NOP
          }
      }
  });
}

//Display the list of non-working days occuring between the telework request start and end dates
function showOverlappingMessage(teleworkInfo) {
    if (typeof teleworkInfo.overlap !== 'undefined') {
        if (Boolean(teleworkInfo.overlap)) {
            $("#lblOverlappingAlert").show();
        } else {
            $("#lblOverlappingAlert").hide();
        }
    }
}

//Display the list of non-working days occuring between the leave request start and end dates
function showOverlappingLeavesMessage(teleworkInfo) {
    if (typeof teleworkInfo.overlapleaves !== 'undefined') {
        if (Boolean(teleworkInfo.overlapleaves)) {
            $("#lblOverlappingLeavesAlert").show();
        } else {
            $("#lblOverlappingLeavesAlert").hide();
        }
    }
}

//Check if the telework request overlaps with a time organisation
function showOverlappingTimeOrganisationsMessage(teleworkInfo) {
    if (typeof teleworkInfo.overlaptimeorganisations !== 'undefined') {
        if (Boolean(teleworkInfo.overlaptimeorganisations)) {
            $("#lblOverlappingTimeOrganisationsAlert").show();
        } else {
            $("#lblOverlappingTimeOrganisationsAlert").hide();
        }
    }
}

//Check if the telework request overlaps with a non-working day
function showOverlappingDayOffMessage(teleworkInfo) {
    if (typeof teleworkInfo.overlapDayOff !== 'undefined') {
        if (Boolean(teleworkInfo.overlapDayOff)) {
            $("#lblOverlappingDayOffAlert").show();
        } else {
            $("#lblOverlappingDayOffAlert").hide();
        }
    }
}

//Check if the telework request exceeds the number of days allowed
function showLimitExceedingMessage(teleworkInfo) {
    if (typeof teleworkInfo.limitExceeding !== 'undefined') {
        if (Boolean(teleworkInfo.limitExceeding)) {
            $("#lblLimitExceedingAlert").show();
        } else {
            $("#lblLimitExceedingAlert").hide();            
        }
    }
}

//Check the dates of telework requests that correspond to the valid campaigns
function showForCampaignDatesMessage(teleworkInfo) {
    if (typeof teleworkInfo.forcampaigndates !== 'undefined') {
        if (Boolean(teleworkInfo.forcampaigndates)) {
            $("#lblForCampaignDatesAlert").hide();        	
        } else {
            $("#lblForCampaignDatesAlert").show();            
        }
    }
}

//Check the deadline for a telework request
function showDelayRespectedMessage(teleworkInfo) {
    if (typeof teleworkInfo.deadlinerespected !== 'undefined') {
        if (Boolean(teleworkInfo.deadlinerespected)) {
            $("#lblDeadlineRespectedAlert").hide();        	
        } else {
            $("#lblDeadlineRespectedAlert").show();            
        }
    }
}

//Check the rights for half-day telework request
function showHalfdayTeleworkMessage(teleworkInfo) {
    if (typeof teleworkInfo.fractionalpart !== 'undefined' && teleworkInfo.fractionalpart > 0) {
        if (Boolean(teleworkInfo.halfday)) {
            $("#lblHalfdayTeleworkAlert").hide();        	
        } else {
            $("#lblHalfdayTeleworkAlert").show();            
        }
    } else {
        $("#lblHalfdayTeleworkAlert").hide();
	}
}

//Enable or disable the primary buttons
function primarybuttons(teleworkInfo) {
    if (typeof teleworkInfo.errors !== 'undefined') {
        if (teleworkInfo.errors == 0) {
        	$(".btn-primary").prop("disabled", false);    	
        } else {
        	$(".btn-primary").prop("disabled", true);
          
        }
    }
}

$(function () {
    getTeleworkLength(false);

    //Init the start and end date picker and link them (end>=date)
    $("#viz_startdate").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: dateJsFormat,
        altFormat: "yy-mm-dd",
        altField: "#startdate",
        numberOfMonths: 1,
              onClose: function( selectedDate ) {
                $( "#viz_enddate" ).datepicker( "option", "minDate", selectedDate );
              }
    }, $.datepicker.regional[languageCode]);
    $("#viz_enddate").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: dateJsFormat,
        altFormat: "yy-mm-dd",
        altField: "#enddate",
        numberOfMonths: 1,
              onClose: function( selectedDate ) {
                $( "#viz_startdate" ).datepicker( "option", "maxDate", selectedDate );
              }
    }, $.datepicker.regional[languageCode]);

    //Force decimal separator whatever the locale is
    $( "#days" ).keyup(function() {
        var value = $("#days").val();
        value = value.replace(",", ".");
        $("#days").val(value);
    });

    $('#viz_startdate').change(function() {getTeleworkLength(true);});
    $('#viz_enddate').change(function() {getTeleworkLength();});
    $('#startdatetype').change(function() {getTeleworkLength();});
    $('#enddatetype').change(function() {getTeleworkLength();});

    //Check if the user has not exceed the number of entitled days
    $("#duration").keyup(function() {getTeleworkInfos(true);});

    $("#frmTeleworkForm").submit(function(e) {
        if (validate_form()) {
            return true;
        } else {
            e.preventDefault();
            return false;
        }
    });
});
