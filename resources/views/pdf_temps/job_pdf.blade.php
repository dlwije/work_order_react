<?php
/*
*Lakmal
*08/10/2022 7:52 p.m.
*/
?>
    <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $biller_data->company }} - Customer Job</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        /**
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
        @page { margin: 0cm 0cm; }
        /** Define now the real margins of every page in the PDF **/
        body {
            margin-top: 3cm;
            margin-left: 2cm;
            margin-right: 2cm;
            margin-bottom: 2cm;
            font-size: 14px !important;
        }
        /** Define the header rules **/
        header {
            position: fixed;
            top: 0.6cm;
            left: 1.6cm;
            right: 1.6cm;
            height: 3cm;
        }
        /** Define the footer rules **/
        footer {
            position: fixed;
            bottom: 0.4cm;
            left: 1.6cm;
            right: 1.6cm;
            height: 2cm;
        }
        .table td, .table th{ padding: 0.25rem !important; }
        p { margin: 0; }
    </style>
</head>
<body >
<header>
    <table width="790px">
        <tr>
            <td width="30%" style="text-align: left;">
                <span>
                    <img style="vertical-align: top; margin-top: -10px;" src="{{ public_path('img/companyLogo.jpg') }}" width="180"/>
                </span>
            </td>
            <td width="30%" style="overflow-wrap: break-word;">
                <span style="font-weight: bold; font-size: 14pt;">{{ $biller_data->name }}</span>
                <br/> {{ $biller_data->address.', '.$biller_data->city }}
                <br> Tel - {{ $biller_data->phone }}<br>
            </td>
        </tr>
    </table>
</header>
<footer>
    <hr style="border: 1px solid black">
    <div style="font-size: 9pt; text-align: left; margin-top: -15px;">
        PRINTED ON - <?PHP date_default_timezone_set('Asia/Colombo');  echo date("dS F Y H:i");?>
    </div>
</footer>

<br>
<br>
<div class="wrapper">
    <!-- Main content -->
    <section class="invoice">
        <!-- Table row -->
        <?PHP if(isset($jobData['headData'][0])) $dateTime = explode(' ',$jobData['headData'][0]->created_at); ?>
        <div class="row mt-n5">
            <div class="col-xs-12 table-responsive">
                <p class="text-center m-0">Service / Repair Order</p>
                <table style="width: 100%;" class=" table-bordered">

                    <tbody>
                    <tr>
                        <th style="width: 15%;vertical-align: middle;">Job Number</th>
                        <td style="width: 12%;vertical-align: middle;"><?PHP if(isset($jobData['headData'][0]))echo $jobData['headData'][0]->serial_no; ?></td>
                        <th style="width: 6%;vertical-align: middle;">Date</th>
                        <td style="width: 12%;vertical-align: middle;"><?PHP if(isset($dateTime[0])) echo $dateTime[0] ?></td>
                        <th style="width: 6%;vertical-align: middle;">Time</th>
                        <td style="width: 10%;vertical-align: middle;"><?PHP if(isset($dateTime[1])) echo $dateTime[1] ?></td>
                        <th style="width: 18%;vertical-align: middle;">Service Adviser</th>
                        <td style="width: 12%;vertical-align: middle;"><?PHP //if(isset($jobData['headData'][0])) echo $jobData['headData'][0]->full_name; ?></td>
                    </tr>

                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <div class="row mt-1">
            <div class="col-xs-12 table-responsive">
                <table style="width: 100%;" class=" table-bordered">
                    <thead>
                    <tr>
                        <th>Customer Details</th>
                        <th>Vehicle Details</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td width="60%">
                            <p>Customer Name : <?PHP if(isset($jobData['headData'][0])) echo $jobData['headData'][0]->name; ?></p>
                            <p>Contact No : <?PHP if(isset($jobData['headData'][0])) echo $jobData['headData'][0]->phone; ?></p>
                            <p>Address : <?PHP if(isset($jobData['headData'][0])) echo $jobData['headData'][0]->address; ?></p>
                        </td>
                        <td width="40%">
                            <p>Vehicle No : <?PHP if(isset($jobData['headData'][0])) echo $jobData['headData'][0]->vehicle_no; ?></p>
                            <p>Vehicle Make : <?PHP if(isset($jobData['headData'][0])) echo $jobData['headData'][0]->vehicle_make; ?></p>
                            <p>Vehicle Model : <?PHP if(isset($jobData['headData'][0])) echo $jobData['headData'][0]->vehicle_model; ?></p>
                            <p>Chasis No : <?PHP if(isset($jobData['headData'][0])) echo $jobData['headData'][0]->chassis_no; ?></p>
                            <p>Engine No : <?PHP if(isset($jobData['headData'][0])) echo $jobData['headData'][0]->engine_no; ?></p>
                            <p>Vehicle Type : <?PHP if(isset($jobData['headData'][0])) echo $jobData['headData'][0]->type_name; ?></p>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <div class="row mt-1">
            <div class="col-xs-12 table-responsive">
                <table style="width: 100%;" class=" table-bordered">
                    <thead>
                    <tr>
                        <th class="text-center">Appointment</th>
                        <th class="text-center">Odometer / Mileage</th>
                        <!--						<th class="text-center">Type Of Service</th>-->
                        <th class="text-center">Estimated Time</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="text-center"><?PHP if(isset($jobData['headData'][0])) echo $jobData['headData'][0]->appointment_id; ?></td>
                        <td class="text-center"><?PHP if(isset($jobData['headData'][0])) echo $jobData['headData'][0]->mileage." KM"; ?></td>
                        <!--						<td class="text-center">--><?PHP //if(isset($jobData[0])) echo $jobData[0]->service_name; ?><!--</td>-->
                        <td class="text-center"><?PHP //if(isset($jobData['headData'][0])) echo $jobData['headData'][0]->estimated_time; ?></td>
                    </tr>

                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <div class="row mt-1">
            <div class="col-xs-12 table-responsive">
                <table style="width: 100%;" class=" table-bordered">
                    <thead>
                    <tr style="border-bottom: 1px solid #dee2e6 !important;">
                        <th>Service Type Name</th>
                        <th style="border-bottom: 1px solid #dee2e6 !important;">Service description</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($jobData['serviceData'] AS $serv_list)
                        <tr>
                            <td>{{ $serv_list->service_name }}</td>
                            <td>{{ $serv_list->description }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <div class="row mt-1">
            <div class="col-xs-12 table-responsive">
                <table style="width: 100%;" class=" table-bordered">
                    <thead>
                    <tr>
                        <th>Reported Defects</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?PHP if(isset($jobData['headData'][0])){ if (empty($jobData['headData'][0]->reported_defect)) echo'<br>';else echo $jobData['headData'][0]->reported_defect; } else echo '<br>';?></td>
                    </tr>

                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
    </section>
</div>

</body>
</html>
<?php //exit();?>
