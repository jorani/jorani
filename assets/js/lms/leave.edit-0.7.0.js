/**
 * This Javascript code is used on the create/edit leave request
 * @copyright  Copyright (c) 2014-2023 Benjamin BALET
 * @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link            https://github.com/bbalet/jorani
 * @since         0.3.0
 */

//Try to calculate the length of the leave
function getLeaveLength(refreshInfos) {
    refreshInfos = typeof refreshInfos !== 'undefined' ? refreshInfos : true;
    var start = moment($('#startdate').val());
    var end = moment($('#enddate').val());
    var startType = $('#startdatetype option:selected').val();
    var endType = $('#enddatetype option:selected').val();

    if (start.isValid() && end.isValid()) {
        if (start.isSame(end)) {
            if (startType == "Morning" && endType == "Morning") {
                $("#spnDayType").html("<img src='" + baseURL + "assets/images/leave_1d_MM.png' />");
            }
            if (startType == "Afternoon" && endType == "Afternoon") {
                $("#spnDayType").html("<img src='" + baseURL + "assets/images/leave_1d_AA.png' />");
            }
            if (startType == "Morning" && endType == "Afternoon") {
                $("#spnDayType").html("<img src='" + baseURL + "assets/images/leave_1d_MA.png' />");
            }
            if (startType == "Afternoon" && endType == "Morning") {
                $("#spnDayType").html("<img src='" + baseURL + "assets/images/date_error.png' />");
            }
        } else {
             if (start.isBefore(end)) {
                if (startType == "Morning" && endType == "Morning") {
                    $("#spnDayType").html("<img src='" + baseURL + "assets/images/leave_2d_MM.png' />");
                }
                if (startType == "Afternoon" && endType == "Afternoon") {
                    $("#spnDayType").html("<img src='" + baseURL + "assets/images/leave_2d_AA.png' />");
                }
                if (startType == "Morning" && endType == "Afternoon") {
                    $("#spnDayType").html("<img src='" + baseURL + "assets/images/leave_2d_MA.png' />");
                }
                if (startType == "Afternoon" && endType == "Morning") {
                    $("#spnDayType").html("<img src='" + baseURL + "assets/images/leave_2d_AM.png' />");
                }
             }
        }
        if (refreshInfos) getLeaveInfos(false);
    }
}

//Get the leave credit, duration and detect overlapping cases (Ajax request)
//Default behavour is to set the duration field. pass false if you want to disable this behaviour
function getLeaveInfos(preventDefault) {
        $('#frmModalAjaxWait').modal('show');
        var start = moment($('#startdate').val());
        var end = moment($('#enddate').val());
        $.ajax({
        type: "POST",
        url: baseURL + "leaves/validate",
        data: {   id: userId,
                    type: $("#type option:selected").text(),
                    startdate: $('#startdate').val(),
                    enddate: $('#enddate').val(),
                    startdatetype: $('#startdatetype').val(),
                    enddatetype: $('#enddatetype').val(),
                    leave_id: leaveId
                }
        })
        .done(function(leaveInfo) {
            if (typeof leaveInfo.length !== 'undefined') {
                var duration = parseFloat(leaveInfo.length);
                duration = Math.round(duration * 1000) / 1000;  //Round to 3 decimals only if necessary
                if (!preventDefault) {
                    if (start.isValid() && end.isValid()) {
                        $('#duration').val(duration);
                    }
                }
            }
            if (typeof leaveInfo.credit !== 'undefined') {
                var credit = parseFloat(leaveInfo.credit);
                var duration = parseFloat($("#duration").val());
                if (duration > credit) {
                    $("#lblCreditAlert").show();
                } else {
                    $("#lblCreditAlert").hide();
                }
                if (leaveInfo.credit != null) {
                    $("#lblCredit").text('(' + leaveInfo.credit + ')');
                }
            }
            //Check if the current request overlaps with another one
            showOverlappingMessage(leaveInfo);
            //Or overlaps with a non-working day
            showOverlappingDayOffMessage(leaveInfo);
            //Check if the employee has a contract
            if (leaveInfo.hasContract == false) {
                bootbox.alert(noContractMsg);
            } else {
                //If the employee has a contract, check if the current leave request is not on two yearly leave periods
                var periodStartDate = moment(leaveInfo.PeriodStartDate);
                var periodEndDate = moment(leaveInfo.PeriodEndDate);
                if (start.isValid() && end.isValid() && periodEndDate.isValid()) {
                    if (start.isBefore(periodEndDate) && periodEndDate.isBefore(end)) {
                        bootbox.alert(noTwoPeriodsMsg);
                    }
                    if (start.isBefore(periodStartDate)) {
                        bootbox.alert(noTwoPeriodsMsg);
                    }
                }
            }
            showListDayOff(leaveInfo);
            $('#frmModalAjaxWait').modal('hide');
        });
}

//When editing/viewing a leave request, refresh the information about overlapping and days off in the period
function refreshLeaveInfo() {
        $('#frmModalAjaxWait').modal('show');
        var start = moment($('#startdate').val());
        var end = moment($('#enddate').val());
        $.ajax({
        type: "POST",
        url: baseURL + "leaves/validate",
        data: {   id: userId,
                    type: $("#type option:selected").text(),
                    startdate: $('#startdate').val(),
                    enddate: $('#enddate').val(),
                    startdatetype: $('#startdatetype').val(),
                    enddatetype: $('#enddatetype').val(),
                    leave_id: leaveId
                }
        })
        .done(function(leaveInfo) {
            showOverlappingMessage(leaveInfo);
            showOverlappingDayOffMessage(leaveInfo);
            showListDayOff(leaveInfo);
            $('#frmModalAjaxWait').modal('hide');
        });
}

//Display the list of non-working days occuring between the leave request start and end dates
function showListDayOff(leaveInfo) {
    if (typeof leaveInfo.listDaysOff !== 'undefined') {
        var arrayLength = leaveInfo.listDaysOff.length;
        if (arrayLength>0) {
            var htmlTable = "<a href='#divDaysOff' data-toggle='collapse'  class='btn btn-primary input-block-level'>";
            htmlTable += listOfDaysOffTitle.replace("%s", leaveInfo.lengthDaysOff);
            htmlTable += "&nbsp;<i class='icon-chevron-down icon-white'></i></a>\n";
            htmlTable += "<div id='divDaysOff' class='collapse'>";
            htmlTable += "<table class='table table-bordered table-hover table-condensed'>\n";
            htmlTable += "<tbody>";
            for (var i = 0; i < arrayLength; i++) {
                htmlTable += "<tr><td>";
                htmlTable += moment(leaveInfo.listDaysOff[i].date, 'YYYY-MM-DD').format(dateMomentJsFormat);
                htmlTable += " / <b>" + leaveInfo.listDaysOff[i].title + "</b></td>";
                htmlTable += "<td>" + leaveInfo.listDaysOff[i].length + "</td>";
                htmlTable += "</tr>\n";
            }
            htmlTable += "</tbody></table></div>";
            $("#spnDaysOffList").html(htmlTable);
            var htmlTooltip = "<a href='#' id='showNoneWorkedDay' data-toggle='tooltip' data-toggle='tooltip' data-placement='right' title='";
            htmlTooltip += listOfDaysOffTitle.replace("%s", leaveInfo.lengthDaysOff);
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
  url: baseURL + "leaves/validate",
  data: {   id: userId,
              type: $("#type option:selected").text(),
              startdate: $('#startdate').val(),
              enddate: $('#enddate').val(),
              startdatetype: $('#startdatetype').val(),
              enddatetype: $('#enddatetype').val(),
              leave_id: leaveId
          }
  })
  .done(function(leaveInfo) {
      $('#frmModalAjaxWait').modal('hide');
      if (typeof leaveInfo.listDaysOff !== 'undefined') {
          var arrayLength = leaveInfo.listDaysOff.length;
          if (arrayLength>0) {
              var htmlTable = "<div id='divDaysOff2'>";
              htmlTable += "<table class='table table-bordered table-hover table-condensed'>\n";
              htmlTable += "<thead class='thead-inverse'>";
              htmlTable += "<tr><th>";
              htmlTable += listOfDaysOffTitle.replace("%s", leaveInfo.lengthDaysOff);
              htmlTable += "</th></tr></thead>";
              htmlTable += "<tbody>";
              for (var i = 0; i < arrayLength; i++) {
                  htmlTable += "<tr><td>";
                  htmlTable += moment(leaveInfo.listDaysOff[i].date, 'YYYY-MM-DD').format(dateMomentJsFormat);
                  htmlTable += " / <b>" + leaveInfo.listDaysOff[i].title + "</b></td>";
                  htmlTable += "<td>" + leaveInfo.listDaysOff[i].length + "</td>";
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

//Display the list of non-working days occuring between the leave request start and end dates
function showOverlappingMessage(leaveInfo) {
    if (typeof leaveInfo.overlap !== 'undefined') {
        if (Boolean(leaveInfo.overlap)) {
            $("#lblOverlappingAlert").show();
        } else {
            $("#lblOverlappingAlert").hide();
        }
    }
}

//Check if the leave request overlaps with a non-working day
function showOverlappingDayOffMessage(leaveInfo) {
    if (typeof leaveInfo.overlapDayOff !== 'undefined') {
        if (Boolean(leaveInfo.overlapDayOff)) {
            $("#lblOverlappingDayOffAlert").show();
        } else {
            $("#lblOverlappingDayOffAlert").hide();
        }
    }
}

$(function () {
    getLeaveLength(false);

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

    $('#viz_startdate').change(function() {
	// set end date equal to start date for Short Leave    
     if ($("#type option:selected").text() == "Short Leave")
     {
       
           var sd=$("#startdate").val();
       
           $("#viz_enddate" ).datepicker('setDate', new Date(sd));
     }
	    
	    getLeaveLength(true);
      
    });
    $('#viz_enddate').change(function() {getLeaveLength();});
    $('#startdatetype').change(function() {getLeaveLength();});
    $('#enddatetype').change(function() {getLeaveLength();});
    $('#type').change(function() {getLeaveInfos(false);});

    //Check if the user has not exceed the number of entitled days
    $("#duration").keyup(function() {getLeaveInfos(true);});

    $("#frmLeaveForm").submit(function(e) {
        // To vaildate enddate , duration if Leave type is not equal to Short Leave
      if ($("#type option:selected").text() != "Short Leave")
      {	    
	if (validate_form()) {
            return true;
        } else {
            e.preventDefault();
            return false;
        }
       }else{
              shortleave();
              return true;

       }

    });
});
