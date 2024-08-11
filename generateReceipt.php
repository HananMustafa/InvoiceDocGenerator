<?php
require_once './PHPWord-master/src/PhpWord/PhpWord.php';
require_once './PHPWord-master/src/PhpWord/TemplateProcessor.php';
require_once './PHPWord-master/src/PhpWord/Settings.php';
require_once './PHPWord-master/src/PhpWord/Shared/ZipArchive.php';
require_once './PHPWord-master/src/PhpWord/Shared/Text.php';

use PhpOffice\PhpWord\TemplateProcessor;

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture form data
    $patient_name = $_POST['patient_name'];
    $case_type = $_POST['case_type'];
    $arch = $_POST['arch'];
    $quantity = $_POST['quantity'];
    $model_3d = isset($_POST['3d_model']) ? 'Yes' : 'No';
    $alignova_box = isset($_POST['alignova_box']) ? 'Yes' : 'No';
    $discount = $_POST['discount'];


    echo "Patient Name: " . htmlspecialchars($patient_name) . "<br>";
    echo "Case Type: " . htmlspecialchars($case_type) . "<br>";
    echo "Arch: " . htmlspecialchars($arch) . "<br>";
    echo "Quantity: " . htmlspecialchars($quantity) . "<br>";
    echo "3D Model: " . htmlspecialchars($model_3d) . "<br>";
    echo "Alignova Box: " . htmlspecialchars($alignova_box) . "<br>";
    echo "Discount: " . htmlspecialchars($discount) . "<br>";

    // Load the template
    $templateProcessor = new TemplateProcessor('template.docx');

    $total = 0;
    // Replace placeholders with form data
    $templateProcessor->setValue('Pname', $patient_name);
    $templateProcessor->setValue('CaseType', $case_type);
    $templateProcessor->setValue('item1', $arch);
    $templateProcessor->setValue('qty1', $quantity);

    if($model_3d== "Yes" && $alignova_box=="Yes"){
    $templateProcessor->setValue('item2', '3D Model');
    $templateProcessor->setValue('item3', 'Box');
    $templateProcessor->setValue('qty2', '1');
    $templateProcessor->setValue('qty3', '1');
    $templateProcessor->setValue('price2', '5000');
    $templateProcessor->setValue('price3', '1500');
    $total=5000+1500;
    }

    if($case_type== "case1"){
        $templateProcessor->setValue('item1', $arch);
        $templateProcessor->setValue('qty1', "1");
        $templateProcessor->setValue('price1', "20000");
        $total= $total+20000;
    }
    else if ($case_type== "case2"){
        $templateProcessor->setValue('item1', $arch);
        $templateProcessor->setValue('qty1', "1");
        $templateProcessor->setValue('price1', "40000");
        $total= $total+40000;
    }
    else{
        $templateProcessor->setValue('item1', $arch);
        $templateProcessor->setValue('qty1', "1");
        $templateProcessor->setValue('price1', "70000");
        $total= $total+70000;
    }
    
    $templateProcessor->setValue('discount', $discount);
    $total= $total-$discount;
    $templateProcessor->setValue('total', $total);
    

    
    $currentDate = date('d-m-Y');

    //set date
    $templateProcessor->setValue('date', $currentDate);

    // Generate a unique filename
    $fileName = $patient_name . '_' . $currentDate . '.docx';
    $templateProcessor->saveAs($fileName);

    
    // Redirect to download page with the filename
    header('Location: downloadReceipt.php?file=' . urlencode($fileName));
    exit;
} else {
    // Handle the case where the request is not POST
    echo "Invalid request method.";
}
?>
