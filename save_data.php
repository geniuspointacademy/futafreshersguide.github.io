<?php
// Allow CORS if needed
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if ($data) {
        // Prepare the data record
        $record = [
            'timestamp' => date('Y-m-d H:i:s'),
            'name' => $data['name'] ?? '',
            'status' => $data['status'] ?? '',
            'level' => $data['level'] ?? 'N/A',
            'department' => $data['department'] ?? '',
            'whatsapp' => $data['whatsapp'] ?? '',
            'access_time' => date('Y-m-d H:i:s')
        ];
        
        // Save to CSV file
        $filename = 'visitor_data.csv';
        
        // Check if file exists to write headers
        $writeHeaders = !file_exists($filename);
        
        $file = fopen($filename, 'a');
        
        // Write headers if new file
        if ($writeHeaders) {
            fputcsv($file, array_keys($record));
        }
        
        // Write the data
        fputcsv($file, $record);
        fclose($file);
        
        // Return success response
        echo json_encode(['success' => true, 'message' => 'Data saved successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No data received']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
