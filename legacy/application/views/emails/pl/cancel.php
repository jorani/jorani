<?php
/**
 * Email template.You can change the content of this template
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @since         0.1.0
 */
?>
<html lang="pl">

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta charset="UTF-8">
    <style>
        table {
            width: 50%;
            margin: 5px;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 20px;
        }

        h5 {
            color: red;
        }
    </style>
</head>

<body>
    <h3>{Title}</h3>
    {Firstname} {Lastname} anulowano czas żądania. Zobacz <a href="{BaseUrl}leaves/{LeaveId}">szczegóły</a>
    poniżej:<br />
    <table border="0">
        <tr>
            <th>Od &nbsp;</th>
            <td>{StartDate}&nbsp;({StartDateType})</td>
        </tr>
        <tr>
            <th>Do &nbsp;</th>
            <td>{EndDate}&nbsp;({EndDateType})</td>
        </tr>
        <tr>
            <td>Typ &nbsp;</td>
            <td>{Type}</td>
        </tr>
        <tr>
            <td>Czas trwania &nbsp;</td>
            <td>{Duration}</td>
        </tr>
        <tr>
            <td>Stan &nbsp;</td>
            <td>{Balance}</td>
        </tr>
        <tr>
            <td>Przyczyna &nbsp;</td>
            <td>{Reason}</td>
        </tr>
        <tr>
            <td>Poprzedni komentarz &nbsp;</td>
            <td>{Comments}</td>
        </tr>
        <tr>
            <td><a href="{BaseUrl}requests/cancellation/accept/{LeaveId}">Potwierdzenie anulowania</a> &nbsp;</td>
            <td><a href="{BaseUrl}requests?cancel_rejected={LeaveId}">Odrzucenie anulowania</a></td>
        </tr>
    </table>
    <br />
    Możesz sprawdzić <a href="{BaseUrl}hr/counters/collaborators/{UserId}">bilans wyjść</a> przed zatwierdzeniem wniosku
    o urlop.
    <hr>
    <h5>*** Ta wiadomość została wygenerowana automatycznie, prosimy nie odpowiadać na tę wiadomość ***</h5>
</body>

</html>