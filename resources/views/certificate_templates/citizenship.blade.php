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

        /* Outer Border */
        .certificate {
            border: 12px double #8b0000;
            padding: 25px;
            position: relative;
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.08;
            font-size: 90px;
            font-weight: bold;
            color: #8b0000;
            white-space: nowrap;
            pointer-events: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Header */
        .header td {
            vertical-align: middle;
        }

        .qr,
        .photo {
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
            background: #8b0000;
            color: #fff;
            padding: 6px 22px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 3px;
        }

        /* Meta info */
        .meta {
            margin-top: 10px;
            font-size: 13px;
        }

        .meta td {
            padding: 4px;
        }

        /* Info Table */
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

        /* Declaration */
        .note {
            margin-top: 25px;
            text-align: justify;
            font-size: 14px;
            line-height: 1.8;
        }

        /* Footer */
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

        {{-- <div class="watermark">Citizenship</div> --}}

        <!-- HEADER -->
        <table class="header">
            <tr>
                <td class="qr" >
                    {!! $qr_code_image !!}
                </td>

                <td class="center">
                    <div class="govt">গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</div>
                    <div class="union">{{$union_name ?? 'নাই'}}</div>
                    <div class="location">{{$union_address ?? 'নাই'}}</div>

                    <div class="title">{{ $certificate_type_bangla }}</div>
                </td>

                <td class="photo">PHOTO</td>
            </tr>
        </table>

        <!-- META -->
        <table class="meta">
            <tr>
                <td>সার্টিফিকেট নম্বর: <b>{{ $certificate_number }}</b></td>
                <td style="text-align:right">প্রদানের তারিখ: <b>{{ $issue_date }}</b></td>
            </tr>
        </table>

        <!-- INFORMATION -->
        <table class="info">
            <tr>
                <td>নাম</td>
                <td>{{ $applicant->name_bangla ?: 'নাই' }}</td>
            </tr>
            <tr>
                <td>পিতার নাম</td>
                <td>{{ $applicant->father_name_bangla ?: 'নাই' }}</td>
            </tr>
            <tr>
                <td>মাতার নাম</td>
                <td>{{ $applicant->mother_name_bangla ?: 'নাই' }}</td>
            </tr>
            <tr>
                <td>জাতীয় পরিচয়পত্র (এনআইডি)</td>
                <td>{{ $applicant->nid_number ?: 'নাই' }}</td>
            </tr>
            <tr>
                <td>জন্ম তারিখ</td>
                <td>{{ $applicant->dob ?: 'নাই' }}</td>
            </tr>
            <tr>
                <td>স্থায়ী ঠিকানা</td>
                <td>

                    {{$applicant->permanent_address_bangla ?? 'নাই'}}
                </td>
            </tr>
        </table>


        <!-- DECLARATION -->
        <div class="note">
            এ মর্মে প্রত্যয়ন করা যাচ্ছে যে, উপরোক্ত ব্যক্তি গাজীপুর জেলার গাজীপুর সদর উপজেলার অন্তর্গত তেলিহাটি ইউনিয়ন পরিষদের একজন স্থায়ী বাসিন্দা ও বৈধ নাগরিক। এই ইউনিয়ন পরিষদের রেকর্ড অনুযায়ী, উপর্যুক্ত তথ্য আমাদের সর্বোচ্চ জ্ঞান ও বিশ্বাস অনুযায়ী সত্য ও সঠিক।
        </div>

        <!-- FOOTER -->
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
