var audio_context;
var recorder;

function startUserMedia(stream) {
    var input = audio_context.createMediaStreamSource(stream);

    // Uncomment if you want the audio to feedback directly
    //input.connect(audio_context.destination);

    recorder = new Recorder(input);
}

function startRecording(e) {
    var parent = findParentBySelector(e.target, '.qtypeInterview');
    var stopRecord = parent.querySelector('.stopRecord');
    var audioWrapper = parent.querySelector('.audioWrapper');
    audioWrapper.innerHTML = '';
    stopRecord.className='';
    recorder && recorder.record();
}

function stopRecording(e) {
    e.target.className = 'stopRecord hidden'
    recorder && recorder.stop();
    autoUpload(e);
    recorder.clear();
}

function autoUpload(e, uploadURL) {
    var parent = findParentBySelector(e.target, '.qtypeInterview');
    var audioWrapper = parent.querySelector('.audioWrapper');
    var recorder_data = parent.querySelector('.recorder_data');
    var uploadURL = e.target.getAttribute('data');
    recorder && recorder.exportWAV(function(blob) {
        var url = URL.createObjectURL(blob);
        var au = document.createElement('audio');
        au.controls = true;
        au.src = url;
        audioWrapper.appendChild(au);

        var previous = recorder_data.value != '' ? JSON.parse(recorder_data.value) : ['',''];
        var fd = new FormData();
        fd.append('repo_upload_file', blob);
        fd.append('previous', previous[1]);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', uploadURL, true);
        xhr.onload = function(){
            var json = JSON.parse(xhr.response);
            recorder_data.value = JSON.stringify([json.url,json.file]);
        }
        xhr.send(fd);
    });
  }

  window.onload = function init() {
    try {
      // webkit shim
      window.AudioContext = window.AudioContext || window.webkitAudioContext;
      navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia;
      window.URL = window.URL || window.webkitURL;

      audio_context = new AudioContext;
    } catch (e) {
      alert('No web audio support in this browser!');
    }

    var interviewItems = document.querySelectorAll('.qtypeInterview .answer .recorder_data');
    var audioWrapper = document.querySelectorAll('.qtypeInterview .answer .audioWrapper')[0];
    for(var i=0; i<interviewItems.length; i++){
        var recorder_data = interviewItems[i];
        if(recorder_data.value != ''){
            var json = JSON.parse(recorder_data.value);
            var au = document.createElement('audio');
            au.controls = true;
            au.src = json[0];
            audioWrapper.appendChild(au);
        }
    }
    navigator.getUserMedia({audio: true}, startUserMedia, function(e) {
    });
  };

  bindElementBySelector('.qtypeInterview .playControll','click',function(e){
      e.preventDefault();

      var parent = findParentBySelector(e.target, '.qtypeInterview');
      var questionPlayer = parent.querySelector('.questionPlayer');
      if(typeof(questionPlayer)!='undefined' && questionPlayer != null){questionPlayer.remove()};
      var au = document.createElement('audio');
      au.className = 'questionPlayer hidden';
      au.src = e.target.getAttribute('data');
      parent.appendChild(au);
      var questionPlayer = parent.querySelector('.questionPlayer');
      questionPlayer.play();
      e.target.disabled = true;
      questionPlayer.onended = () => {
          startRecording(e);
      }
  });

  bindElementBySelector('.qtypeInterview .stopRecord','click',function(e){
      e.preventDefault();
      stopRecording(e);
  });
