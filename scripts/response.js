var audio_context;
var recorder;

function startUserMedia(stream) {
    var input = audio_context.createMediaStreamSource(stream);

    // Uncomment if you want the audio to feedback directly
    //input.connect(audio_context.destination);

    recorder = new Recorder(input);
}

function startRecording(button, uploadURL) {
    recordingslist.innerHTML = '';
    qtypeInterviewQuestionStopRecord.className='';
    recorder && recorder.record();
}

function stopRecording(e) {
    qtypeInterviewQuestionStopRecord.className='hidden';
    recorder && recorder.stop();

    autoUpload(e.target.getAttribute('data'));
    recorder.clear();
}

function autoUpload(uploadURL) {
    recorder && recorder.exportWAV(function(blob) {
        var url = URL.createObjectURL(blob);
        var au = document.createElement('audio');
        var recorderData = id_recorder_data.value;
        var previous = id_recorder_data.value != '' ? JSON.parse(id_recorder_data.value) : ['',''];
        au.controls = true;
        au.src = url;
        recordingslist.appendChild(au);

        var fd = new FormData();
        fd.append('repo_upload_file', blob);
        fd.append('previous', previous[1]);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', uploadURL, true);
        xhr.onload = function(){
            var json = JSON.parse(xhr.response);
            id_recorder_data.value = JSON.stringify([json.url,json.file]);
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

    if(id_recorder_data.value != ''){
        var json = JSON.parse(id_recorder_data.value);

        var au = document.createElement('audio');
        au.controls = true;
        au.src = json[0];
        recordingslist.appendChild(au);
        console.log(id_recorder_data);
    }
    navigator.getUserMedia({audio: true}, startUserMedia, function(e) {
    });
  };

  qtypeInterviewQuestionControll.addEventListener('click', function(e) {
      e.preventDefault();

      if(typeof(qtypeInterviewQuestionPlayer)!='undefined'){qtypeInterviewQuestionPlayer.remove()};
      var au = document.createElement('audio');
      au.id = 'qtypeInterviewQuestionPlayer';
      au.src = e.target.getAttribute('data');
      au.className = 'hidden';
      document.getElementsByTagName('body')[0].appendChild(au);
      qtypeInterviewQuestionPlayer.play();
      qtypeInterviewQuestionControll.disabled = true;
      qtypeInterviewQuestionPlayer.onended = () => {
          startRecording();
      }

  });

  qtypeInterviewQuestionStopRecord.addEventListener('click', function(e) {
      e.preventDefault();
      stopRecording(e);
  })
