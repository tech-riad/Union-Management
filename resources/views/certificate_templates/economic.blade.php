<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>{{ $certificate_type_bangla }} - {{ $certificate_number }}</title>

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

  {{-- <div class="watermark">{{ $watermark_text ?? 'Watermark Text' }}</div> --}}

  <table class="header">
    <tr>
      <td class="qr">
                    {!! $qr_code_image !!}
                </td>

      <td class="center">
        <div class="govt">গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</div>
        <div class="union">{{$union_name ?? 'নাই'}}</div>
        <div class="location">{{$union_address ?? 'নাই'}}</div>

        <div class="title">Financial Insolvency Certificate</div>
      </td>

      <td class="photo">{{$user_profile->profile_photo}}</td>
    </tr>
  </table>

  <table class="meta">
    <tr>
      <td>লাইসেন্স নম্বর: <b>{{$certificate_number}}</b></td>
      <td style="text-align:right">প্রদানের তারিখ: <b>{{$issue_date}}</b></td>
    </tr>
  </table>

  <table class="info">
    <tr><td>Name</td><td>{{$user->name}}</td></tr>
    <tr><td>Father's Name</td><td> {{$user_profile->father_name ?? 'N/A'}}</td></tr>
    <tr><td>Occupation</td><td> {{$user->occupation}}</td></tr>
    <tr><td>Monthly Income</td><td> {{$user->monthly_income}} </td></tr>
    <tr><td>Address</td><td>{{$user->address}}</td></tr>
    <tr><td>National ID (NID)</td><td>XXXXXXXXXX</td></tr>
    <tr><td>Validity</td><td>01 January 2024 – 31 December 2027</td></tr>
  </table>

  <div class="note">
    This is to certify that the above-mentioned individual is financially insolvent.
  </div>

  <table class="footer">
            <tr>
                <td>
                    ইস্যুকারী:<br><br>
                    ইউনিয়ন পরিষদ কার্যালয় <br>
                    {{ $union_address ?: 'নাই' }}
                </td>

                <td class="sign">
                    _______________________<br>
                    চেয়ারম্যান <br>
                    {{ $union_name ?: 'নাই' }}

                    <div class="seal">
                        সরকারি সিল
                    </div>
                </td>
            </tr>
        </table>

</div>

</body>
</html>
