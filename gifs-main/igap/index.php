<?php
include "../includes/application_top2.php";
require_once 'vendor/autoload.php';
$db = new db2();
$action = $_REQUEST["action"];
if($action == "check_email")
{
    $email_exists = get_tuple("participant", request("email"), "email");
    // debug_r($email_exists);
    if(!$email_exists)
    {
        echo json_encode(array('success' => 'true'));
    } else {
        echo json_encode(array('success' => 'false', 'error'=> "Email already exists"));
    }
    // debug($responseKeys);
    die;
}
else if ($action == "submit" ) {
    // debug($_FILES);
    $all_data       = ($_REQUEST['data']);
    $data           = (array)json_decode($all_data);
    // $email;$comment;$captcha;
    $email          = $data["email"];
    // $comment     = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
    $captcha        = $data["token"];
    $secretKey      = "6LdUrCAbAAAAAIqf4XYjkAtKfcKpsxugEa9iExJo";
    $ip             = $_SERVER['REMOTE_ADDR'];
    $name           = $data["name"];
    $father_name    = $data["father_name"];
    $mobile_number  = $data["mobile_number"];
    $date_of_birth  = $data["date_of_birth"];
    $gender_id      = $data["gender_id"];
    $gender         = get_tuple("gender", $gender_id, "gender_id");
    $gender_name    = $gender["gender_name"];
    $cnic           = $data["cnic"];
    $email          = $data["email"];
    $mysql_date_of_birth = php_to_mysql_date($date_of_birth);

    $city           = $data["city"];
    $country_iso_code2     = $data["country_id"];
    $country        = get_tuple("country", strtoupper($country_iso_code2), "code");
    $country_id     = $country["country_id"];
    $country_name   = $country["country_name"];
    $school_maderssa = $data['school_maderssa'];
    // post request to server
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array('secret' => $secretKey, 'response' => $captcha);

    $options = array(
        'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
        )
    );
    $context  = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    $responseKeys = json_decode($response,true);
    header('Content-type: application/json');
    if($responseKeys["success"]) {
		$date_proof_upload = upload($_FILES["date_proof_upload"], "users_images");
        $profile_photo_upload = upload($_FILES["profile_photo_upload"], "users_images");
        //Data is valid
        //insert data into database
        $sql = "INSERT INTO 
        `participant`
         ( `name`, `father_name`, `gender_id`, `gender_name`, `profile_photo_upload`, `date_of_birth`, `date_proof_upload`, `mobile_number`, `email`, `city`, `country_id`, `country_name`, `school_maderssa`) 
         VALUES
          ('$name', '$father_name', '$gender_id', '$gender_name', '$profile_photo_upload', '$mysql_date_of_birth', '$date_proof_upload', '$mobile_number', '$email', '$city', '$country_id', '$country_name', '$school_maderssa');";
        $db->sqlq($sql);
        // debug_r($sql);
        // Create the message
        // debug_r($data);
        $message = (new Swift_Message())
        // Add subject
        ->setSubject('New Form from iGAP 2021')

        //Put the From address
        ->setFrom([$email])

        // Include several To addresses
        ->setTo(['it.greenisland@gmail.com' => 'New Mailtrap user'])
        ->setCc([
        'hasnainwasaya@gmail.com' => 'IT Consultant'
        ])->setFrom('info@greenislandtrust.org');
        // Set the body
        $message->setBody(
        '<html>
        <body>
            <table>
                <tr>
                <td> IP   </td>
                <td> '.$ip.' </td>
                </tr>
                <tr>
                    <td> Name   </td>
                    <td> '.$name       .' </td>
                </tr>
                <tr>
                    <td>Father\'s/Husband\'s Name</td>
                    <td> '.$father_name    .' </td>
                </tr>
                <td> Gender   </td>
                    <td> '.$gender_name  .' </td>
                </tr>
                <tr>
                    <td> Date of Birth </td>
                    <td> '.$date_of_birth  .' </td>
                </tr>
                <tr>
                <tr>
                    <td> CNIC Number/Passport Number   </td>
                    <td> '.$cnic       .' </td>
                </tr>
                <tr>
                    <td> Mobile Number  </td>
                    <td> '.$mobile_number  .' </td>
                </tr>
                <tr>
                    <td> Email   </td>
                    <td> '.$email      .' </td>
                </tr>
                <tr>
                   <td> City   </td>
                   <td> '.$city       .' </td>
                </tr>
                <tr>
                   <td>Country   </td>
                   <td> '.$country_name.' </td>
                </tr>
                <tr>
                   <td>School/Madressa/Institution </td>
                   <td> '.$school_maderssa.' </td>
                </tr>
            </table>
        </body>
        </html>',
        'text/html' // don't forget to mark the content type
        );
        
		// For the regular file types like docs, spreadsheets, or images, you can go without the content type
        // if($date_proof_upload)
        if($date_proof_upload)
            $message->attach(Swift_Attachment::fromPath($date_proof_upload)->setFilename('date_proof_upload.jpg'));
        if($profile_photo_upload)
            $message->attach(Swift_Attachment::fromPath($profile_photo_upload)->setFilename('profile_photo_upload.jpg'));
        try {
            $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
                ->setUsername('it.greenisland@gmail.com')
                ->setPassword('gifsit123');

            // $transport = (new Swift_SmtpTransport('mail.najafi.org', 587, 'ssl'))
            //     ->setUsername('info@najafi.org')
            //     ->setPassword('4rYZOKhR^~Ix');
            $mailer = new Swift_Mailer($transport);
            // $mailer = new Swift_Mailer();
            $mailer->send($message);

            $headers = "From: International Ghadeer Awareness Program <info@greenislandtrust.org>\r\n".
            "MIME-Version: 1.0" . "\r\n" .
            "Content-type: text/html; charset=UTF-8" . "\r\n";
            // echo "Mail : ";
            mail($email, "iGAP 2021 | Successfully Registered", "
            <p>Dear Participants<br>
  Congratulations, You are successfully registered for iGAP 2021. You will receive pdf of the content and credentials i.e. Username and Password within 48 hours Insha Allah.</p>
<p>Thanks &amp; Regards<br>
  GIFS Management<br>
  +92 334 4300668<br>
  (voice &amp; text msgs only)</p>
            ", $headers);
            // die;
            // echo '
            // SMTP';
            // $transport = Swift_SmtpTransport::newInstance('localhost', 25);
            // $mailer = new Swift_Mailer($transport);
            // $mailer = new Swift_Mailer();
            // $mailer->send($message);
        }
        catch(Exception $ex)
        {
            echo json_encode(array('success' => 'false', 'error'=> $ex->getMessage()));
        }
        // [name] => MUHAMMAD 01-07-2020 TEST MASTER CARD HASNAIN
        // [father_name] => AS
        // [mobile_number] => 012312311
        // [date_of_birth] => 11/06/2021
        // [gender_id] => 2
        // [cnic] => 4230136550176
        // [email] => muhammad.hasnain@thebaronhotels.com
        // [city] => Iraq
        // [school_maderssa] => 123
        echo json_encode(array('success' => 'true'));
    } else {
        echo json_encode(array('success' => 'false', 'error'=> $responseKeys["error-codes"][0]));
    }
    // debug($responseKeys);
    die;
}
?>
<!DOCTYPE html><!--  This site was created in Webflow. http://www.webflow.com  -->
<!--  Last Published: Tue Jun 08 2021 11:18:44 GMT+0000 (Coordinated Universal Time)  -->
<html data-wf-page="60bf4755ee7781f174053e85" data-wf-site="60bf4755ee77816575053e84">

<head>
    <meta charset="utf-8">
    <title>iGap Online Registration</title>
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="Webflow" name="generator">
    <link href="css/normalize.css" rel="stylesheet" type="text/css">
    <link href="css/webflow.css" rel="stylesheet" type="text/css">
    <link href="css/igap-online-registration-7cf65d.webflow.css" rel="stylesheet" type="text/css">
    <!-- [if lt IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js" type="text/javascript"></script><![endif] -->
    <!-- <script src="https://d3e54v103j8qbb.cloudfront.net/js/jquery-3.5.1.min.dc5e7f18c8.js?site=60bf4755ee77816575053e84"
        type="text/javascript" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous">
    </script> -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script type="text/javascript">
    ! function(o, c) {
        var n = c.documentElement,
            t = " w-mod-";
        n.className += t + "js", ("ontouchstart" in o || o.DocumentTouch && c instanceof DocumentTouch) && (n
            .className += t + "touch")
    }(window, document);
    </script>
    <link href="images/favicon.ico" rel="shortcut icon" type="image/x-icon">
    <link href="images/webclip.png" rel="apple-touch-icon">
    <link rel="stylesheet" href="build/css/intlTelInput.css">
    <script src="build/js/intlTelInput.js"></script>
    <script src="https://www.google.com/recaptcha/api.js?render=6LdUrCAbAAAAAJaRRbHntFxMZqcJ_oZTTD9zRFZv"></script>
    <script>
    function show_group()
    {
        var date_of_birth = $("#date_of_birth").datepicker('getDate');
        var date_today = new Date();
        console.log( (date_today - date_of_birth)/ (1000 * 3600 * 24 * 365) );
        // var junior_start = new Date(2007, 06, 31);
        var junior_end = new Date(2011, 07, 1);
        // var middle_start = new Date(2003, 06, 31);
        var middle_end = new Date(2007, 06, 31);
        var senior_end = new Date(2003, 06, 31);
        var group = ""
        if(date_of_birth <= senior_end)
        {
            group = "Senior";
        }
        else if(date_of_birth <= middle_end)//date_of_birth >= middle_start &&
        {
            group = "Middle";
        }
        else if(date_of_birth <= junior_end)//date_of_birth >= junior_start && 
        {
            // 31/07/2003
            // 1/08/2007
            group = "Junior";
        }
        group_name = group;
        $("#group_div").html("Group : "+group);
    }
    var iti;
    var group_name;
    $(function() {
        $('#name, #father_name').keyup(function() {
            this.value = this.value.toUpperCase();
        });


        var input = document.querySelector("#mobile_number");
        iti = window.intlTelInput(input, {
            // allowDropdown: false,
            // autoHideDialCode: false,
            // autoPlaceholder: "off",
            // dropdownContainer: document.body,
            // excludeCountries: ["us"],
            // formatOnDisplay: false,
            geoIpLookup: function(callback) {
                $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "";
                    callback(countryCode);
                });
            },
            // hiddenInput: "full_number",
            // initialCountry: "auto",
            localizedCountries: {
                'pk': 'Pakistan'
            },
            // nationalMode: false,
            // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
            // placeholderNumberType: "MOBILE",
            preferredCountries: ["pk", "us", "gb", "au"],
            separateDialCode: true,
            utilsScript: "build/js/utils.js",
        });
        input.addEventListener("countrychange", function() {
            // // var temp = $("#mobile_number").intlTelInput("getSelectedCountryData");
            // var input = document.querySelector("#mobile_number");
            // intl = window.intlTelInput(input);
            
            $("#country_id").val(iti.getSelectedCountryData().iso2); 
        });
        var d = new Date();
        var n = d.getFullYear();

        datepicker_options = {
            altFormat: "dd/mm/yyyy",
            // appendText: "(dd/mm/yyyy)",
            dateFormat: "dd/mm/yy",
            // # defaultDate: +1,
            yearRange: "1921:2011" ,//+ n
            changeMonth: true,
            changeYear: true
        }
        $('#date_of_birth').mask('00/00/0000');
        $("#date_of_birth").datepicker(datepicker_options);
        if ("createEvent" in document) {
            var evt = document.createEvent("HTMLEvents");
            evt.initEvent("change", false, true);
            document.getElementById("mobile_number").dispatchEvent(evt);
        }
        else
            document.getElementById("mobile_number").fireEvent("onchange");

    });
    function submit_form()
    {
        // token = '';
        // e.preventDefault();
        const data = {
            token: '',
            name: $("#name").val(),
            father_name : $("#father_name").val(),
            mobile_number : $("#mobile_number").val(),
            date_of_birth : $("#date_of_birth").val(),
            group_name : group_name,
            gender_id : $("#gender_id").val(),
            cnic : $("#cnic").val(),
            mobile_number : $("#mobile_number").val(),
            email : $("#email").val(),
            city : $("#city").val(),
            country_id : $("#country_id").val(),
            school_maderssa : $("#school_maderssa").val(),
        };
        
        var fd = new FormData();

        var date_proof_upload = $('#date_proof_upload')[0].files;
        var profile_photo_upload = $('#profile_photo_upload')[0].files;
        if(alert_and_focus("name", "Name")) return false;
        if(alert_and_focus("father_name", "Father's/Husband's Name")) return false;
        if(alert_and_focus("gender_id", "Gender")) return false;
        if(profile_photo_upload.length > 0){
            fd.append('profile_photo_upload',profile_photo_upload[0]);
        }
        if(alert_and_focus("date_of_birth", "Date of Birth")) return false;
        if(date_proof_upload.length > 0){
            fd.append('date_proof_upload',date_proof_upload[0]);
        }
        else
        {
            // Now not required
            // alert("Error! " + msg + " is required");
            // return false;
        }
        if(alert_and_focus("cnic", "CNIC/Passport Number")) return false;
        if(alert_and_focus("mobile_number", "Mobile Number")) return false;
        if(alert_and_focus("email", "Email")) return false;
        if(alert_and_focus("city", "City")) return false;
        if(alert_and_focus("country", "Country")) return false;
        if(alert_and_focus("school_maderssa", "School/Madressa/Institution")) return false;
        if($("#terms:checked").val() == undefined)
        {
            alert("Please accept Terms &amp; Conditions to proceed");
            return false;
        }

        $.ajax({
            type: "POST",
            url: "?action=check_email",
            data : {email : $("#email").val()},
        }).done(function(d) {
            try {
               r = JSON.parse(d);
            } catch (e) {
                alert("Contact the Web Developer");
                return false;
            }
            if(r.success == "true")
            {
                // alert("Form has been submitted successfully");
                // return false;
                grecaptcha.ready(function() {
                    grecaptcha.execute('6LdUrCAbAAAAAJaRRbHntFxMZqcJ_oZTTD9zRFZv', {
                            action: 'submit',
                    }).then(function(token) {
                    // Add your logic to submit to your backend server here.
                    // $secret = '6LdUrCAbAAAAAIqf4XYjkAtKfcKpsxugEa9iExJo';
                    // $ip = 
                    // Check file selected or not
                    // fd.append('token', token);
                    data.token = token;
                    show_group();
                    data.group_name = group_name;
                    fd.append('data', JSON.stringify(data))
                    $.ajax({
                        type: "POST",
                        url: "?action=submit",
                        contentType: false,
                        processData: false,
                        data : fd,
                    }).done(function(r) {
                        if(r.success == "true")
                        {
                            alert("Form has been submitted successfully");
                            location.href= 'thanks.php';
                        }
                        else
                        {
                            alert("Error! "+r.error);
                        }
                        // if (typeof success === 'function') {
                        //     success.apply(this, arguments);
                        // }
                        console.log(r);
                    }).fail(function() {
                        // if (typeof error === 'function') {
                        //     error.apply(this, arguments);
                        // }
                    });
                });
            });
            // location.href= 'thanks.php';
            }
            else
            {
                alert("Error! "+r.error);
            }
            // if (typeof success === 'function') {
            //     success.apply(this, arguments);
            // }
            console.log(r);
        });
        return false;
    }
    function alert_and_focus(id, msg)
    {
        if($("#"+id).val() == "")
        {
            alert("Error! "+msg+" is required.");
            $("#"+id).focus();
            return true;
        }
        return false;
    }
    
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"
        integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"
        integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="js/jquery.mask.min.js" type="text/javascript"></script>
</head>

<body>
    <div id="Header">
        <div><img src="images/banner-01.jpg" loading="lazy" sizes="100vw"
                srcset="images/banner-01.jpg 500w, images/banner-01.jpg 800w, images/banner-01.jpg 1080w" alt=""></div>
    </div>
    <div class="section">
        <div class="div-block">
            <h1 class="heading-4">iGap Online Registration Form</h1>
            <div class="w-form">
                <form id="email-form" name="email-form" data-name="Email Form" onsubmit="return submit_form();">
                    <label for="name">Name</label><input name="name" type="text" class="w-input" id="name"
                        placeholder="Name in ALL CAPS" maxlength="256" data-name="Name" required="">
                    
                    <label for="father_name">Father's/Husband's Name</label><input type="father_name" class="w-input"
                        maxlength="256" name="father_name" data-name="Father's/Husband's Name" placeholder="Name in ALL CAPS"
                        id="father_name" required="">
                    
                        <label for="gender_id">Gender</label>
                    <?php echo dropdown("gender", '', '', ' class="w-input"');?>
                    
                    <label for="profile_photo_upload">Profile Photo</label>
                    <input type="file" class="w-input" name="profile_photo_upload" id="profile_photo_upload">
                    
                    <label for="date_of_birth">Date of Birth <small>dd/mm/yyyy</small></label><input type="text" class="w-input" maxlength="256"
                        name="date_of_birth" data-name="Email 9" placeholder="dd/mm/yyyy" id="date_of_birth"
                        required="" onchange="show_group()">

                    <div id="group_div" style="color: #38a063; font-weight: bold;">Group : </div>

                    <label for="date_proof_upload">Any government authorized document which contains date of birth</label>
                    <input type="file" class="w-input" name="date_proof_upload" id="date_proof_upload">

                    <label for="cnic">CNIC Number/Passport Number</label><input type="text" class="w-input" maxlength="256"
                        name="cnic" data-name="Email 7" placeholder="Please write in correct format" id="cnic"
                        required="">
                    
                    <label for="mobile_number">Mobile Number</label><input type="text" class="w-input" maxlength="256"
                        name="mobile_number" data-name="Email 6" placeholder="123..." id="mobile_number" required="">
                    <br><br>
                    <label for="email">Email Address</label><input type="email" class="w-input" maxlength="256"
                        name="email" data-name="Email 5" id="email" required="">
                    <label for="city">City</label><input type="text" class="w-input" maxlength="256" name="city"
                        data-name="City" placeholder="" id="city" required="">

                    
                    <label for="country_id">Country</label>
                    <?php 
                    // echo dropdown("country", '', '', ' class="w-input"');
                    $temp = '<select id="country_id" name="country_id" class="form-control w-select">';
                    $countries = $db->result("select * from country order by country_name ASC");
                    foreach($countries as $country)
                        $temp .=    '<option value="'.strtolower($country['code']).'">'.$country['country_name'].'</option>';
                    $temp .= '</select>';
                    echo $temp;
                    ?>
                    <label for="school_maderssa">School/Madressa/Institution</label><input type="text" class="w-input"
                        maxlength="256" name="school_maderssa" data-name="Email 2"
                        placeholder="Current School or Maderssa" id="school_maderssa" required="">
                    <div class="text-block">
                     <strong>Terms &amp; Conditions<strong>
                   </p>
                   <ul>
  <li>Males &amp; Females aged 10 years &amp; above are eligible to take part in this quiz program.<br>
    (Eligible date of birth before 1st August 2011)</li>
</ul>
<style>
#eligble_table
{
    margin-left: 3%;
}
#eligble_table td
{
    padding: 5px;
}
</style>

<table id="eligble_table" width="97%" border="1" cellspacing="0" cellpadding="5" style="border:thin solid #000">
  <tbody style="color:#fff">
    <tr style="color:#38a063">
      <td colspan="2" align="center" bgcolor="#FFFFFF" style="color:#38a063; text-align:right; padding-right:10%">Age</td>
      <td align="center" bgcolor="#FFFFFF">Eligible Date of Birth</td>
      <td align="center" bgcolor="#FFFFFF">Content</td>
    </tr>
    <tr>
      <td align="center" bgcolor="#FFFFFF" style="color:#38a063">Junior Group</td>
      <td align="center" bgcolor="#38a063">10-14 Years</td>
      <td align="center" bgcolor="#38a063">1st Aug 2011 - 31st July 2007</td>
      <td align="center" bgcolor="#38a063">Chapters 1-5</td>
    </tr>
    <tr>
      <td align="center" bgcolor="#FFFFFF" style="color:#38a063">Middle Group</td>
      <td align="center" bgcolor="#38a063">14-18 Years</td>
      <td align="center" bgcolor="#38a063">1st Aug 2007 - 31st July 2003</td>
      <td align="center" bgcolor="#38a063">Chapters 1-8</td>
    </tr>
    <tr>
      <td align="center" bgcolor="#FFFFFF" style="color:#38a063">Senior Group</td>
      <td align="center" bgcolor="#38a063">18 Years Above</td>
      <td align="center" bgcolor="#38a063">On &amp; Before 1st Aug 2003</td>
      <td align="center" bgcolor="#38a063">Chapters 1-10</td>
    </tr>
  </tbody>
</table>
<p>&nbsp;</p>
<ul>
  <li> Last date of registration is Wednesday, 28th July, 2021 (GMT 23:59). <br>
    Quiz will be conducted online on Sunday, 1st August, 2021 Insha Allah.</li>
  <li> Duration of the quiz will be 45 minutes, starting from 00:00 GMT and will remain open till 23:59 GMT of the same day i-e 1st August 2021. (for   example, in Pakistan, it will start at 05:00 a.m. on 1st August and will   remain valid till 04:59 a.m. on 2nd August. Residents of all other locations  may calculate their timings by clicking on this link:</li>
  <p style="text-align: center;">www.thetimezoneconverter.com</p>
  <li>Quiz will be opened for one attempt only. In case of any power failure, net 
  issue, etc., it will be auto submitted.</li>
  <li>    Winner has to provide identity and eligibility proof to receive the prize.</li>
  <li>    In case of more than one Winner, prizes will be given through balloting, 
  however, Achievement Certificate will be awarded to all 100% scorer.</li>
  <li>    Certificate of Participation will be issued to every contestant whereas,    Appreciation Certificate will be awarded to all participants securing 75%  and above.</li>
</ul>

                    

                    <label class="w-checkbox checkbox-field"><input name="terms" type="checkbox"
                            class="w-checkbox-input" id="terms" value="1" data-name="Checkbox"><span
                            class="checkbox-label w-form-label">I agree with the above Terms &amp; Conditions</span></label>

                    </div>
                    <div
                        class="w-form-formrecaptcha recaptcha g-recaptcha g-recaptcha-error g-recaptcha-disabled g-recaptcha-invalid-key">
                    </div><input type="submit" value="Submit" data-wait="Please wait..."
                        class="submit-button w-button">
                </form>
                <div class="w-form-done">
                    <div>Thank you! Your submission has been received!</div>
                </div>
                <div class="w-form-fail" style="display:none">
                    <div>Oops! Something went wrong while submitting the form.</div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="div-block-2">
            <div class="text-block-2">For further details, please contact WhatsApp +92 3344300668<br>(only voice and
                text msgs)</div>
        </div>
    </div>
    <!-- <script src="js/webflow.js" type="text/javascript"></script> -->
    <!-- [if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif] -->
</body>

</html>