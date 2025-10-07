<?php
$vendorBase = getenv('VENDOR_BASE_URL');

return [
    'bootstrap_icons'  => "$vendorBase/bootstrap-icons.css",
    'bootstrap_js'     => "$vendorBase/bootstrap.bundle.min.js",
    'bootstrap_css'    => "$vendorBase/bootstrap.min.css",
    'jquery_js'        => "$vendorBase/jquery-3.6.0.min.js",
    'popper_js'        => "$vendorBase/popper.min.js", 
    'sweetalert_js'    => "$vendorBase/sweetalert2@11.js",
    'datatables_css'   => "$vendorBase/datatables.min.css",
    'datatables_js'    => "$vendorBase/datatables.min.js",
    'select2_css'      => "$vendorBase/select2.min.css",
    'select2_js'       => "$vendorBase/select2.min.js",
    'toastr_css'       => "$vendorBase/toastr.min.css",
    'toaster_js'       => "$vendorBase/toastr.min.js",
    'sjcl_js'          => "$vendorBase/sjcl.min.js",
    'datepickerjs'     =>"$vendorBase/datepicker.js",
    'datepickercss'    =>"$vendorBase/datepicker.css"

    // Add more libraries here as needed
];
