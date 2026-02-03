<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>{{ $certificate_type }}-{{ $certificate_number }}</title>

<style>
  body {
    font-family: 'solaimanlipi', sans-serif;
    background: #fdfdfd;
    margin: 0;
    padding: 25px;
  }

  .certificate {
    border: 12px double #003366;
    padding: 25px;
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

  <div class="watermark">Unmarried</div>

  <table class="header">
    <tr>
      <td class="qr">QR CODE</td>

      <td class="center">
        <div class="govt">গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</div>
        <div class="union">{{$union_name ?? 'নাই'}}</div>
        <div class="location">{{$union_address ?? 'নাই'}}</div>

        <div class="title">{{ $certificate_type_bangla }}</div>
      </td>

      <td class="photo">PHOTO</td>
    </tr>
  </table>

  <table class="meta">
    <tr>
      <td>লাইসেন্স নম্বর: <b>{{ $certificate_number }}</b></td>
      <td style="text-align:right">প্রদানের তারিখ: <b>{{ $issue_date }}</b></td>
    </tr>
  </table>

  <table class="info">
    <tr><td>Name</td><td>{{ $applicant->name_bangla ?: 'নাই' }}</td></tr>
    <tr>
                <td>পিতার নাম</td>
                <td>{{ $applicant->father_name_bangla ?: 'নাই' }}</td>
            </tr>
    <tr>
                <td>মাতার নাম</td>
                <td>{{ $applicant->mother_name_bangla ?: 'নাই' }}</td>
            </tr>
    <tr><td>Gender</td><td>Male / Female</td></tr>
    <tr><td>Address</td><td>Village, Union, Upazila, District</td></tr>
    <tr><td>Date Of Birth</td><td>--/--/--</td></tr>
    <tr><td>National ID (NID)</td><td>XXXXXXXXXX</td></tr>
    <tr><td>Validity</td><td>01 January 2024 – 31 December 2024</td></tr>
  </table>

  <div class="note">
    The applicant is unmarried according to Union Parishad records.
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
