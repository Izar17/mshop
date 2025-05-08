<?php
// connecting to database
$conn = mysqli_connect("localhost", "root", "", "mshop_db") or die("Database Error");

// getting user message through ajax
$getMesg = mysqli_real_escape_string($conn, $_POST['text']);

//checking user query to database query
$getMesg = preg_replace('/[^a-zA-Z0-9:]/', '', $getMesg);

$check_data = "SELECT replies FROM chatbot WHERE queries LIKE '%$getMesg%'";
$run_query = mysqli_query($conn, $check_data) or die("Error");

// if user query matched to database query we'll show the reply otherwise it go to else statement
if(mysqli_num_rows($run_query) > 0){
    //fetching replay from the database according to the user query
    $fetch_data = mysqli_fetch_assoc($run_query);
    //storing replay to a varible which we'll send to ajax
    $replay = $fetch_data['replies'];
    echo $replay;
}else{
    // echo "Sorry can't be able to understand you!";


    // Function to send a query to Botpress and get the response
    function getBotpressResponse($query) {
        $botApiUrl = 'https://files.bpcontent.cloud/2024/12/17/02/20241217023232-FGWGRIWU.json';
        $sessionId = uniqid(); // Unique session ID for the user

        // Prepare the payload
        $data = json_encode([
            'text' => $query,
            'type' => 'text',
            'sessionId' => $sessionId
        ]);

        // Initialize cURL
        $ch = curl_init($botApiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        // Execute the cURL request and get the response
        $response = curl_exec($ch);
        curl_close($ch);

        // Decode the response
        $responseDecoded = json_decode($response, true);

        if (isset($responseDecoded['responses'][0]['text'])) {
            return $responseDecoded['responses'][0]['text'];
        } else {
            return "Sorry, I can't understand you!";
        }
    }

    // Get the user query from AJAX
    $userQuery = isset($_POST['text']) ? $_POST['text'] : '';

    if ($userQuery) {
        // Get the response from Botpress
        $botResponse = getBotpressResponse($userQuery);
        echo $botResponse;
    } else {
        echo "Please enter a query.";
    }
}

?>