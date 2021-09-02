document.addEventListener('DOMContentLoaded', () => {

var mycall = null;

    function sendback(finalcmd){
	console.log("Attempting to send command back");
       mycall = $.ajax({
          url: 'https://djunreal.uk/autodjweb/finalcmd.php?id=' + streamerid + '&cmd=' + encodeURIComponent(finalcmd),
          success: function(result){
	  // perhaps do something visual here?
	  console.log("Command sent");
          },     
          error: function(result){
		// this needs to be reported in some useful way
		console.log("ERROR - " + result);
          }
       });
    }

  var soundNotAllowed = function (error) {
      paper.innerHTML = "<span>You must allow your microphone.</span>";
      console.log(error);
  }

  var soundAllowed = function (stream) {
       paper.innerHTML = "<span>Allowed</span>";
       window.persistAudioStream = stream;
  }

  navigator.getUserMedia({audio:true}, soundAllowed, soundNotAllowed);

  window.SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

  let p = document.createElement('span');
  const paper = document.querySelector('.paper');
  paper.appendChild(p);

  const recognition = new SpeechRecognition();
  recognition.interimResults = true;
  recognition.addEventListener('result', e => {
    console.log(e.results);
    const transcript = Array.from(e.results).
    map(results => results[0]).
    map(result => result.transcript).
    join('');
    let script = transcript.
    replace(/\b(smile|smiling|(ha)+)\b/gi, '😃').
    replace(/\b(happy|celebrate)\b/gi, '🎉').
    replace(/\b(angry|serious)\b/gi, '😠').
    replace(/\bclap\b/gi, '👏').
    replace(/\b(okay|ok|okie)\b/gi, '👍').
    replace(/\b(eat|eating|hungry)\b/gi, '🍔').
    replace(/\bcry\b/gi, '😥');

    p.innerHTML = script + "&nbsp;";
    p.scrollIntoView(true);
    if (e.results[0].isFinal) {
	sendback(transcript);
      p = document.createElement('span');
      paper.appendChild(p);
    }
  });
  recognition.addEventListener('end', () => {
      recognition.start();
  });
  recognition.start();
});

