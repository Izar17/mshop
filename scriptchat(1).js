$(document).ready(function() {
    $("#chat-icon").click(function () {
        $("#chatbot-wrapper").slideToggle();
    });

   var SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
   var recognition = new SpeechRecognition();
   recognition.lang = 'en-US';
   var isRecognitionRunning = false;
   var isBotSpeaking = false;

   function startRecognition() {
       if (!isRecognitionRunning && !isBotSpeaking) {
           recognition.start();
       }
   }

   recognition.onstart = function() {
       isRecognitionRunning = true;
       console.log('Voice recognition activated');
   };

   recognition.onend = function() {
       isRecognitionRunning = false;
       if (!isBotSpeaking) {
           setTimeout(startRecognition, 1000); // Restart recognition after 1 second
       }
   };

   recognition.onresult = function(event) {
       if (!isBotSpeaking) {
           var transcript = event.results[0][0].transcript.toLowerCase();
           if (transcript.includes("hi, kelly") || transcript.includes("ok, kelly")) {
               $("#mic-btn").trigger("click");
           }
           $("#data").val(transcript);
           $("#send-btn").click();
       }
   };

   function speakText(text) {
    // Create a temporary div element to filter out any HTML tags
    var tempDiv = document.createElement("div");
    tempDiv.innerHTML = text;
    text = tempDiv.textContent || tempDiv.innerText; // Extract only clean text

    var voices = window.speechSynthesis.getVoices();
    var voice = voices.find(voice => voice.name.includes('Google UK English Female')) || voices[0];

    var msg = new SpeechSynthesisUtterance(text);
    msg.voice = voice;
    msg.pitch = 2;  // Increase pitch for a child-like voice
    msg.rate = 1;   // Slightly increase the rate to match faster speech typical of kids

    recognition.stop(); // Stop recognition while speaking
    isBotSpeaking = true;

    msg.onend = function() {
        isBotSpeaking = false;
        startRecognition(); // Restart recognition after speaking
    };

    window.speechSynthesis.speak(msg);
}



   $("#mic-btn").on("click", function() {
       startRecognition();
   });

   $("#send-btn").on("click", function() {
       var value = $("#data").val();
       var msg = '<div class="user-inbox inbox"><div class="msg-header"><p>' + value + '</p></div></div>';
       $(".form").append(msg);
       $("#data").val('');

       $.ajax({
           url: 'message.php',
           type: 'POST',
           data: 'text=' + value,
           success: function(result) {
               var replay = '<div class="bot-inbox inbox"><div class="icon"><img src="person.svg" alt="Chat Icon"></div><div class="msg-header"><p>' + result + '</p></div></div>';
               $(".form").append(replay);

               speakText(result); // Use the speakText function

               $(".form").scrollTop($(".form")[0].scrollHeight);
           }
       });
   });

   startRecognition();
});
