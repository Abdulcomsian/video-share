<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Speech To Text</title>
</head>
<body>
    <h1>Audio To Speech</h1>
    <div>
        <input type="file" name="audioFileInput" id="audioFileInput">
        <button id="convertAudio">Convert</button>
    </div>
</body>
</html>

<script>
    function transcribeSpeechAsync(file) {
        const apiKey = 'AIzaSyD3WLOh-lYJzenqF3nggiegWW2zcOT8o8c';
        const url = `https://speech.googleapis.com/v1/speech:longrunningrecognize?key=${apiKey}`;

        // Create a FormData object to send the audio file
        const formData = new FormData();
        formData.append('audio', file);

        // Create the request options
        const options = {
            method: 'POST',
            body: formData,
        };

        // Send the request
        fetch(url, options)
            .then((response) => response.json())
            .then((data) => {
            const operationId = data.name;

            // Function to check the status of the operation
            const checkStatus = () => {
                const operationUrl = `https://speech.googleapis.com/v1/operations/${operationId}?key=${apiKey}`;

                // Send request to check the operation status
                fetch(operationUrl)
                .then((response) => response.json())
                .then((data) => {
                    if (data.done) {
                    const results = data.response.results;
                    const transcriptions = results.map((result) => result.alternatives[0].transcript);
                    console.log(transcriptions); // Handle the transcriptions
                    } else {
                    // Operation is still in progress, check again after some time
                    setTimeout(checkStatus, 2000);
                    }
                })
                .catch((error) => {
                    console.error('Error checking operation status:', error);
                });
            };

            // Start checking the operation status
            checkStatus();
            })
            .catch((error) => {
            console.error('Error initiating transcription:', error);
            });
}

document.getElementById("convertAudio").addEventListener("click" , function(e){
    const fileInput = document.getElementById('audioFileInput');
      const file = fileInput.files[0];
      transcribeSpeechAsync(file);
})
</script>
