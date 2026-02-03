<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Trade License</title>

<style>
  body {
    font-family: 'solaimanlipi', sans-serif;
    background: #fdfdfd;
    margin: 0;
    padding: 25px;
  }

  .certificate {
    border: 12px double #003366;
    padding: 20px;
    position: relative;
  }

  .watermark {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    opacity: 0.08;
    font-size: 80px;
    font-weight: bold;
    color: #003366;
    white-space: nowrap;
    pointer-events: none;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  .header td {
    vertical-align: middle;
  }

  .qr, .photo {
    width: 110px;
    height: 110px;
    border: 1px solid #444;
    text-align: center;
    font-size: 11px;
  }

  .center {
    text-align: center;
  }

  .govt {
    font-size: 20px;
    font-weight: bold;
  }

  .union {
    font-size: 18px;
    font-weight: bold;
    margin-top: 5px;
  }

  .location {
    font-size: 14px;
  }

  .title {
    margin: 12px auto;
    display: inline-block;
    background: #003366;
    color: #fff;
    padding: 6px 22px;
    font-size: 18px;
    font-weight: bold;
    border-radius: 3px;
  }

  .meta {
    margin-top: 10px;
    font-size: 13px;
  }

  .meta td {
    padding: 4px;
  }

  .info {
    margin-top: 20px;
    font-size: 14px;
  }

  .info td {
    border: 1px solid #999;
    padding: 8px;
  }

  .info td:first-child {
    width: 30%;
    font-weight: bold;
    background: #f3f3f3;
  }

  .note {
    margin-top: 25px;
    text-align: justify;
    font-size: 14px;
    line-height: 1.8;
  }

  .footer {
    margin-top: 50px;
  }

  .footer td {
    vertical-align: top;
    font-size: 14px;
  }

  .sign {
    text-align: center;
  }

  .seal {
    margin-top: 20px;
    border: 1px dashed #555;
    display: inline-block;
    padding: 10px 20px;
    font-size: 12px;
  }
</style>
</head>

<body>

<div class="certificate">

  <div class="watermark">Monthly Income</div>

  <table class="header">
    <tr>
      <td class="qr">{{$qr_code_data}}</td>

      <td class="center">
        <div class="govt">Government of the People's Republic of Bangladesh</div>
        <div class="union">{{$union_name}}</div>
        <div class="location">{{$union_address}}</div>

        <div class="title">Monthly Income Certificate</div>
      </td>

      <td class="photo">PHOTO</td>
    </tr>
  </table>

  <table class="meta">
    <tr>
      <td>License No: <b>{{ $certificate_number }}</b></td>
      <td style="text-align:right">Issue Date: <b>{{ $issue_date }}</b></td>
    </tr>
  </table>

  <table class="info">
    <tr><td>Name</td><td>ABC Traders</td></tr>
    <tr><td>Age</td><td>Date</td></tr>
    <tr><td>Father's Name</td><td>Father Name</td></tr>
    <tr><td>Mother's Name</td><td>Mother Name</td></tr>
    <tr><td>Occupation</td><td>job</td></tr>
    <tr><td>Monthly Income</td><td> $$ </td></tr>
    <tr><td>Present Address</td><td>Village, Union, Upazila, District</td></tr>
    <tr><td>Permanent Address</td><td>Village, Union, Upazila, District</td></tr>
    <tr><td>Mobile Number</td><td>XXXXXXXXXXX</td></tr>
    <tr><td>NID</td><td>XXXXXXXXXXX</td></tr>
    <tr><td>Validity</td><td>01 January 2024 – 31 December 2028</td></tr>
  </table>

  <div class="note">
    This is to certify that the above-mentioned individual  applicant’s monthly income is as stated above.
  </div>

  <table class="footer">
    <tr>
      <td>
        Issued By:<br><br>
        Telihati Union Parishad <br>
        Gazipur
      </td>

      <td class="sign">
        _______________________<br>
        Chairman <br>
        Telihati Union Parishad

        <div class="seal">
          Official Seal
        </div>
      </td>
    </tr>
  </table>

</div>

</body>
</html>
